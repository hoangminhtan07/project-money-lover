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
     * @param array
     * @return array
     */
    public function add($data)
    {
        $this->create();
        $this->validator()->remove('amount', 'naturalNumber');
        return $this->save($data);
    }

    /**
     * Get list transactions by walletId
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
     *  Get list transactions (group by date) by walletId from fday to tday
     * 
     * @param int $walletId
     * @param int $categoryId
     * @param string fday
     * @param string tday
     * @return array
     */
    public function getlistTransactionsByDate($walletId, $categoryId, $fday, $tday)
    {
        if ($categoryId == '00') {
            $data = $this->find('all', array(
                'conditions' => array(
                    'wallet_id'              => $walletId,
                    'Transaction.created <=' => $tday,
                    'Transaction.created >=' => $fday,
                ),
                'group'      => 'Transaction.created',
            ));
            return $data;
        }
        $data = $this->find('all', array(
            'conditions' => array(
                'wallet_id'              => $walletId,
                'category_id'            => $categoryId,
                'Transaction.created <=' => $tday,
                'Transaction.created >=' => $fday,
            ),
            'group'      => 'Transaction.created',
        ));
        return $data;
    }

    /**
     *  Get list transactions (group by date) by walletId 
     * 
     * @param int $walletId
     * @return array
     */
    public function getlistTransactionsByCategory($walletId)
    {
        $data = $data = $this->find('all', array(
            'conditions' => array(
                'wallet_id' => $walletId,
            ),
            'order'      => 'Category.name',
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
                'Transaction.wallet_id' => $walletId,
                'Transaction.id'        => $transactionId,
            )
        ));
        return $data;
    }

    /**
     *  Edit Transaction
     * 
     * @param array $data
     * @param int $transactionId
     * @return array
     */
    public function edit($data, $transactionId)
    {
        $this->id = $transactionId;
        $this->validator()->remove('amount', 'naturalNumber');
        return $this->save($data);
    }

    /**
     * delete all transaction by categoryId
     * 
     * @param int $categoryId
     */
    public function deleteTransactionsByCategoryId($categoryId)
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
