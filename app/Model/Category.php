<?php

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
    
    
    /**
     *  Add category
     * 
     * @param array $data
     * @param int $idu
     * @return mix
     */
    public function add($data,$idu){
        $this->create();
        $data['user_id'] = $idu;
        return $this->save($data);
    }

}
