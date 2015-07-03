<?php

class Transaction extends AppModel {

    public $name = 'Transaction';
    public $belongsTo = array(
        'Wallet' => array(
            'className' => 'Wallet',
            'foreignKey' => 'wallet_id'
        ),
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'category_id'
        )
    );

}
