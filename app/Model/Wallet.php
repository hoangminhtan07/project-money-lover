<?php

class Wallet extends AppModel
{

    public $name      = 'Wallet';
    public $belongsTo = 'User';
    public $hasMany   = array(
        'Transaction' => array(
            'name'       => 'Transaction',
            'foreignKey' => 'wallet_id',
            'dependent'  => 'true',
        )
    );
    public $validate  = array(
        'name'    => array(
            'rule'    => 'notBlank',
            'message' => 'Please enter wallet name',
        ),
        'balance' => array(
            'integer' => array(
                'rule'    => 'numeric',
                'message' => 'Integer only.'
            )
        )
    );

    public function add($data = null, $idu = 0)
    {
        $this->create();
        $this->saveField('user_id', $idu);
        return ($this->save($data));
    }

    public function view($idu = 0) //function view all wallet belongs user has id=$idu
    {
        $data = $this->query("SELECT * from wallets AS Wallet WHERE user_id=$idu");
        return $data;
    }

    public function edit($data = null, $idw = 0)
    {
        $this->id = $idw;
        return ($this->save($data));
    }

}
