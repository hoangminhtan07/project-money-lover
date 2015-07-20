<?php

App::uses('AppModel', 'Model');

class Transaction extends AppModel
{

    public $name     = 'Transaction';
    public $validate = array(
        'amount' => array(
            'notEmpty'      => array(
                'rule'    => 'notBlank',
                'message' => 'Please enter amount.'
            ),
            'naturalNumber' => array(
                'rule'    => 'naturalNumber',
                'message' => 'natural number only.',
            ),
        ),
    );

    public function bindBelongsTo($model, $fKey)
    {
        $this->bindModel(array(
            'belongsTo' => array(
                $model => array(
                    'className'  => $model,
                    'foreignKey' => $fKey,
                ),
            ),
        ));
    }

    public function bindCategory()
    {
        $this->bindBelongsTo('Category', 'category_id');
    }

    public function bindWallet()
    {
        $this->bindBelongsTo('Wallet', 'wallet_id');
    }

    /**
     *  Add Transaction
     * 
     * @param array $data
     * @param int $walletId
     * @param int $categoryId
     * @return array
     */
    public function add($data, $walletId, $categoryId)
    {
        $this->create();
        return $this->save(array('Transaction' => array(
                        'wallet_id'   => $walletId,
                        'category_id' => $categoryId,
                        'amount'      => $data['amount'],
                        'note'        => $data['note'],
        )));
    }

    /**
     * get list transaction by walletId
     * 
     * @param int $walletId
     * @return array
     */
    public function getListTransactionsByWalletId($walletId)
    {
        $data = $this->find('all', array(
            'conditions' => array(
                'wallet_id' => $walletId,
            ),
        ));
        return $data;
    }

    /**
     * 
     * check Transaction belong to wallet by walletId and transactionId
     * 
     * @param int $walletId
     * @param int $transactionId
     * @return mix
     */
    public function checkWalletTransaction($walletId, $transactionId)
    {
        $data = $this->find('first', array(
            'conditions' => array(
                'wallet_id'      => $walletId,
                'Transaction.id' => $transactionId,
            )
        ));
        return $data;
    }

    /**
     * 
     * @param array $data
     * @param int $categoryId
     * @param int $transactionId
     * @return array
     */
    public function edit($data, $categoryId, $transactionId)
    {
        $this->id = $transactionId;
        return $this->save(array(
                    'Transaction' => array(
                        'category_id' => $categoryId,
                        'amount'      => $data['amount'],
                        'note'        => $data['note'],
        )));
    }

    /**
     * delete all transaction by categoryId
     * 
     * @param int $categoryId
     */
    public function deleteTransactionsByCetegoryId($categoryId)
    {
        return $this->deleteAll(array(
                    'Transaction.category_id' => $categoryId
        ));
    }

    /**
     *  get transaction by id
     * 
     * @param int $id
     * @return array
     */
    public function getTransactionById($id)
    {
        return $this->findById($id);
    }

    /**
     * delete transaction by id
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteTransactionById($id)
    {
        return $this->delete($id);
    }

    /**
     * Delete transactions by walletId
     * 
     * @param int $walletId
     * @return boolean
     */
    public function deleteTransactionsByWalletId($walletId)
    {
        return $this->deleteAll(array(
                    'Transaction.wallet_id' => $walletId,
        ));
    }

}
