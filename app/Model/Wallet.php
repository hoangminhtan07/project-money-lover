<?php
class Wallet extends AppModel{
    public $name = 'Wallet';
    public $belongTo = 'User';
    public $hasMany = array(
        'Transaction' => array(
            'name' => 'Transaction',
            'foreignKey' => 'wallet_id',
            'dependent' =>'true',
        )
    );
}
