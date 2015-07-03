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

}
