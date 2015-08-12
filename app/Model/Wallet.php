<?php

App::uses('AppModel', 'Model');

class Wallet extends AppModel
{

    public $name     = 'Wallet';
    public $validate = array(
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

    public function bindHasMany($model, $fKey)
    {
        $this->bindModel(array(
            'hasMany' => array(
                $model => array(
                    'className'  => $model,
                    'foreignKey' => $fKey,
                    'dependent'  => 'true'
                ),
            ),
        ));
    }

    public function bindTransaction()
    {
        $this->bindHasMany('Transaction');
    }

    public function bindBelongTo($model, $fKey)
    {
        $this->bindModel(array(
            'belongTo' => array(
                $model => array(
                    'className'  => $model,
                    'foreignKey' => $fKey,
                ),
            ),
        ));
    }

    public function bindUser()
    {
        $this->bindBelongTo('User', 'user_id');
    }

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
        return $this->save(array(
                    'Wallet' => array(
                        'user_id' => $userId,
                        'name'    => $data['name'],
                        'balance' => $data['balance'],
                    )
        ));
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
    public function getWalletsByUserId($userId = 0)
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
    public function getListWalletsNameByUserId($userId = 0)
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

        $dataSource = $this->getDataSource();
        $dataSource->begin();

        //update balance fromWallet
        $this->updateAll(array('Wallet.balance' => "Wallet.balance - {$amounts}"), array(
            'Wallet.id' => $fromWalletId,
        ));

        //update balance toWallet
        $this->updateAll(array('Wallet.balance' => "Wallet.balance + {$amounts}"), array(
            'Wallet.id' => $toWalletId,
        ));

        return $dataSource->commit();
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

    /**
     * Delete wallet by walletId
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteWalletById($walletId)
    {
        $dataSource = $this->getDataSource();
        $dataSource->begin();

        //delete all transactions before delete wallet
        $transaction = ClassRegistry::init('Transaction');
        $transaction->deleteTransactionsByWalletId($walletId);

        //delete wallet
        $this->delete($walletId);
        return $dataSource->commit();
    }

    /**
     *  Get balance of an wallet
     * 
     * @param int $id
     * @return param
     */
    public function getBalanceByWalletId($id)
    {
        $data = $this->findById($id);
        return $data['Wallet']['balance'];
    }

    /**
     *  delete wallets by userId
     * 
     * @param int $userId
     * return boolean
     */
    public function deleteWalletsByUserId($userId)
    {
        return $this->deleteAll(array(
                    'Wallet.user_id' => $userId,
        ));
    }

}
