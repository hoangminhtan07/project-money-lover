<?php

App::uses('AppModel', 'Model');

class Category extends AppModel
{

    public $name = 'Category';

    public function bindHasMany($model, $fKey)
    {
        $this->bindModel(array(
            'hasMany' => array(
                $model => array(
                    'className'  => $model,
                    'foreignKey' => $fKey,
                    'dependent'  => 'true'
                ),
            ),
        ));
    }

    public function bindTransaction()
    {
        $this->bindHasMany('Transaction', 'category_id');
    }

    public function bindBelongsTo($model, $fKey)
    {
        $this->bindModel(array(
            'belongsTo' => array(
                $model => array(
                    'className'  => $model,
                    'foreignKey' => $fKey,
                ),
            ),
        ));
    }

    public function bindUser()
    {
        $this->bindBelongsTo('User', 'user_id');
    }

    /**
     *  Add category
     * 
     * @param array $data
     * @param int $idu
     * @return mix
     */
    public function add($data, $userId)
    {
        $this->create();
        $data['user_id'] = $userId;
        return $this->save($data);
    }

    /**
     * Get list categories of an user
     * 
     * @param int $userId
     * @return array Array of categories
     */
    public function getListCategoriesByUser($userId)
    {
        $data = $this->find('all', array(
            'conditions' => array(
                'user_id' => $userId,
            )
        ));
        return $data;
    }

    /**
     *  Check category belong to user
     * 
     * @param tnt $userId
     * @param int $categoryId
     */
    public function checkUserCategory($userId, $categoryId)
    {
        //find category belong to user
        $data = $this->find('first', array(
            'conditions' => array(
                'Category.id'      => $categoryId,
                'Category.user_id' => $userId,
            )
        ));

        return $data;
    }

    /**
     * edit Category
     * 
     * @param array $data
     * @param int $categoryId
     * @return array
     */
    public function edit($data, $categoryId)
    {
        $this->id = $categoryId;
        return $this->save($data);
    }

    /**
     * get list name category spent by user
     * 
     * @param int $userId
     * @return array list name category by userId
     */
    public function getListNameCategoryByPurpose($userId, $purpose)
    {
        $data = $this->find('list', array(
            'conditions' => array(
                $this->alias . '.user_id' => $userId,
                'Category.purpose'        => $purpose,
            ),
            'fields'     => 'Category.name',
        ));
        return $data;
    }

    /**
     * Get category by id
     * 
     * @param int $id
     * @return array
     */
    public function getCategoryById($id)
    {
        return $this->findById($id);
    }

    /**
     * Delete category by categoryId
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteCategoryById($categoryId)
    {
        $dataSource = $this->getDataSource();
        $dataSource->begin();

        //delete all transaction by CategoryId
        $transaction = ClassRegistry::init('Transaction');
        $transaction->deleteTransactionsByCategoryId($categoryId);

        //delete category apter delete all transactions
        $this->delete($categoryId);

        return $dataSource->commit();
    }

    /**
     *  delete all categories by userId
     * 
     * @param int $userId
     * @return boolean
     */
    public function deleteCategoriesByUserId($userId)
    {
        return $this->deleteAll(array(
                    'Category.user_id' => $userId
        ));
    }

}
