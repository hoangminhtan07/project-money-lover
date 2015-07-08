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
            'notEmpty' => array(
                'rule'    => 'notBlank',
                'message' => 'Please enter wallet name'
            ),
            'unique'   => array(
                'rule'    => 'isUnique',
                'massage' => 'That name already been taken.'
            ),
        ),
        'balance' => array(
            'integer' => array(
                'rule'    => 'numeric',
                'message' => 'Integer only.'
            )
        ),
        'amounts' => array(
            'notEmpty' => array(
                'rule'    => 'notBlank',
                'massage' => 'Please enter amounts'
            ),
            'integer'  => array(
                'rule'    => 'numeric',
                'massage' => 'Integer only.'
            )
        ),
    );

    /**
     * Add wallet
     * 
     * @param array $data
     * @param int $idu
     * @return array
     */
    public function add($data = null, $idu = 0)
    {
        $this->create();
        $this->saveField('user_id', $idu);
        return ($this->save($data));
    }

    /**
     *  Check wallet belong to user
     * 
     * @param tnt $idu
     * @param int $idw
     */
    public function checkUserWallet($idu, $idw)
    {
//find all wallet belong to user
        $data = $this->find('first', array(
            'conditions' => array(
                'Wallet.id'      => $idw,
                'Wallet.user_id' => $idu,
            )
        ));

        return $data;
    }

    /**
     * View all wallet belongs user has id=$idu
     * 
     * @param int $idu
     * @return array
     */
    public function view($idu = 0)
    {
        $data = $this->find('all', array(
            'conditions' => array(
                'user_id' => $idu,
            )
        ));
        return $data;
    }

    /**
     *  Edit wallet
     * 
     * @param array $data
     * @param int $idw
     * @return array
     */
    public function edit($data = null, $idw = 0)
    {
        $this->id = $idw;
        return ($this->save($data));
    }

    /**
     *  Find list wallets
     * 
     */
    public function findWallet($idu = 0)
    {
        $data = $this->find('list', array(
            'fields'     => 'Wallet.name',
            'conditions' => array(
                'Wallet.user_id' => $idu,
            )
        ));
        return $data;
    }

    /**
     *  Transfer money wallets
     * 
     * @param int $fromWalletId
     * @param int $toWalletId
     * @param int $amounts
     */
    public function transfer($fromWalletId, $toWalletId, $amounts)
    {

        //update balance fromWallet
        $data1    = $this->find('first', array(
            'conditions' => array(
                'Wallet.id' => $fromWalletId,
            )
        ));
        $value1   = $data1['Wallet']['balance'] - $amounts;
        $this->id = $fromWalletId;
        $this->saveField('balance', $value1);

        //update balance toWallet
        $data2    = $this->find('first', array(
            'conditions' => array(
                'Wallet.id' => $toWalletId,
            )
        ));
        $value2   = $data2['Wallet']['balance'] + $amounts;
        $this->id = $toWalletId;
        $this->saveField('balance', $value2);

        return true;
    }

}
