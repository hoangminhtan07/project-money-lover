<?php

App::uses('AppModel', 'Model');

class Category extends AppModel
{

    public $name      = 'Category';
    public $belongsTo = 'User';
    public $hasMany   = array(
        'Transaction' => array(
            'name'       => 'Transaction',
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
            'unique'   => array(
                'rule'    => 'isUnique',
                'massage' => 'That name already been taken.'
            ),
        ),
    );

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
                'Category.user_id' => $userId,
                'Category.purpose' => '0',
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

}
