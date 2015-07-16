<?php

App::uses('AppModel', 'Model');

class Category extends AppModel
{

    public $name      = 'Category';
    public $belongsTo = 'User';
    public $hasMany   = array(
        'Transaction' => array(
            'className'       => 'Transaction',
            'foreignKey' => 'category_id',
            'dependent'  => 'true',
        )
    );
    public $validate  = array(
        'name' => array(
            'notEmpty' => array(
                'rule'    => 'notBlank',
                'message' => 'Please enter wallet name'
            ),
        ),
    );
    
    public function bindHasMany($model, $fKey){
        $this->bindModel(array(
            'hasMany' => array(
                $model => array(
                    'className' => $model,
                    'foreignKey' => $fKey,
                    'dependent'  => 'true'
                ),
            ),
        ));
    }
    
    public function bindTransaction(){
        $this->bindHasMany('Transaction');
    }
    
    public function bindBelongTo($model, $fKey){
        $this->bindModel(array(
            'belongTo' => array(
                $model => array(
                    'className' => $model,
                    'foreignKey' => $fKey,
                ),
            ),
        ));
    }
    
    public function bindUser(){
        $this->bindBelongTo('User','user_id');
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
     * Get categories of an user
     * 
     * @param int $userId User Id
     * @return array Array of categories
     */
    public function getCategoriesByUser($userId)
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
    public function getListNameCategorySpent($userId)
    {
        $data = $this->find('list', array(
            'conditions' => array(
                $this->alias . '.user_id' => $userId,
                'Category.purpose'        => '0',
            ),
            'fields'     => 'Category.name',
        ));
        return $data;
    }

    /**
     *  get list name category earned by userId
     * 
     * @param int $userId
     * @return arrat
     */
    public function getListNameCategoryEarned($userId)
    {
        $data = $this->find('list', array(
            'conditions' => array(
                'Category.user_id' => $userId,
                'Category.purpose' => '1',
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

    public function deleteCategoryById($id)
    {
        return $this->deleteById($id);
    }

}

