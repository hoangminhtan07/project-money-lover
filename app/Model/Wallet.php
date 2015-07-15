<?php

App::uses('AppModel', 'Model');

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
                'message' => 'Please enter amounts'
            ),
            'integer'  => array(
                'rule'    => 'numeric',
                'message' => 'Integer only.'
            )
        ),
    );

    /**
     * Add wallet
     * 
     * @param array $data
     * @param int $userId
     * @return array
     */
    public function add($data = null, $userId = 0)
    {
        $this->create();
        $this->saveField('user_id', $userId);
        return ($this->save($data));
    }

    /**
     *  Check wallet belong to user
     * 
     * @param tnt $userId
     * @param int $walletId
     */
    public function checkUserWallet($userId, $walletId)
    {
        //find wallet belong to user
        $data = $this->find('first', array(
            'conditions' => array(
                'Wallet.id'      => $walletId,
                'Wallet.user_id' => $userId,
            )
        ));

        return $data;
    }

    /**
     * View all wallet belongs user
     * 
     * @param int $userId
     * @return array
     */
    public function view($userId = 0)
    {
        $data = $this->find('all', array(
            'conditions' => array(
                'user_id' => $userId,
            )
        ));
        return $data;
    }

    /**
     *  Edit wallet
     * 
     * @param array $data
     * @param int $walletId
     * @return array
     */
    public function edit($data = null, $walletId = 0)
    {
        $this->id = $walletId;
        return ($this->save($data));
    }

    /**
     *  Get list wallets of users
     * 
     * @param int $userId
     * @return array 
     */
    public function getWalletByUserId($userId = 0)
    {
        $data = $this->find('list', array(
            'fields'     => 'Wallet.name',
            'conditions' => array(
                'Wallet.user_id' => $userId,
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

    /**
     * get wallet by wallet Id
     * 
     * @param int $id
     * @return array
     */
    public function getWalletById($id)
    {
        $data = $this->findById($id);
        return $data;
    }

    /**
     * Deposit money to wallet has id = walletId
     * 
     * @param int $walletId
     * @param int $amount
     */
    public function transactionMoney($walletId = 0, $amount = 0)
    {
        $this->id = $walletId;
        $data     = $this->findById($walletId);
        $data['Wallet']['balance'] += $amount;
        $this->save($data);
    }

}

?>