<?php

class Wallet extends AppModel {

    public $name = 'Wallet';
    public $belongsTo = 'User';
    public $hasMany = array(
        'Transaction' => array(
            'name' => 'Transaction',
            'foreignKey' => 'wallet_id',
            'dependent' => 'true',
        )
    );
    public $validate = array(
        'name' => array(
            'rule' => 'notBlank',
            'message' => 'Please enter wallet name',
        ),
        'balance' => array(
            'integer' => array(
                'rule' => 'numeric',
                'message' => 'Integer only.'
            )
        )
    );

    public function add($data = null,$idu=0) {
        $this->create();
        $this->saveField('user_id',$idu);
        if ($this->save($data)) {
            return ($this->save($data));
        } else {
            return ($this->save($data));
        }
    }

    public function view($id = 0) {
        $data = $this->query("SELECT * from wallets AS Wallet WHERE user_id=$id");
        return $data;
    }

}
