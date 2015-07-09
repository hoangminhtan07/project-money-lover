<?php
App::uses('AppModel', 'Model');

class Transaction extends AppModel
{

    public $name      = 'Transaction';
    public $belongsTo = array(
        'Wallet'   => array(
            'className'  => 'Wallet',
            'foreignKey' => 'wallet_id'
        ),
        'Category' => array(
            'className'  => 'Category',
            'foreignKey' => 'category_id'
        )
    );
    public $validate  = array(
        'amount' => array(
            'notEmpty'      => array(
                'rule'    => 'notBlank',
                'message' => 'Please enter amount.'
            ),
            'naturalNumber' => array(
                'rule'    => 'naturalNumber',
                'message' => 'natural number only.',
            ),
        )
    );
    
    

}
