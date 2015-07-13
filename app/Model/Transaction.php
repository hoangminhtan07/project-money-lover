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
    public function getListTransactions($walletId = 0)
    {
        $data = $this->find('all', array(
            'conditions' => array(
                'wallet_id' => $walletId,
            ),
        ));
        return $data;
    }
    
    /**
     * get list transaction order by categories name
     * 
     * @param int $walletId
     * @return array
     */
    public function getListTransactionsOrderByCategoriesName($walletId = 0)
    {
        $data = $this->find('all', array(
            'conditions' => array(
                'wallet_id' => $walletId,
            ),
            'order' => 'Category.name'
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
    
    

}
