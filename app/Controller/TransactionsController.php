<?php

class TransactionsController extends AppController
{

    private $income  = 0;
    private $expense = 0;

    /**
     *   Add transaction
     */
    public function add()
    {

        //get userId
        $userId = $this->Auth->user('id');

        //get list name category of user
        $this->loadModel('Category');
        $getListNameCategorySpent  = $this->Category->getListNameCategorySpent($userId);
        $getListNameCategoryEarned = $this->Category->getListNameCategoryEarned($userId);
        $this->set('listCategorySpent', $getListNameCategorySpent);
        $this->set('listCategoryEarned', $getListNameCategoryEarned);

        //get default walletId
        $this->loadModel('User');
        $data     = $this->User->findUserById($userId);
        $walletId = $data['User']['current_wallet_id'];

        //check request
        if (!$this->request->is('post')) {
            return;
        }

        //get data
        //get categoryId
        $data             = $this->request->data['Transaction'];
        $categorySpentId  = $this->request->data['Transaction']['categorySpentId'];
        $categoryEarnedId = $this->request->data['Transaction']['categoryEarnedId'];
        $amount           = $this->request->data['Transaction']['amount'];

        if (empty($categorySpentId) && !empty($categoryEarnedId)) {
            $categoryId = $categoryEarnedId;

            //deposit money to wallet has Id = walletId
            $this->loadModel('Wallet');
            $this->Wallet->transactionMoney($walletId, $amount);
        } elseif (empty($categoryEarnedId) && !empty($categorySpentId)) {
            $categoryId = $categorySpentId;

            //withdraw money from wallet has Id = walletId
            $this->loadModel('Wallet');
            $amount = -$amount;
            $this->Wallet->transactionMoney($walletId, $amount);
        } elseif (empty($categoryEarnedId) && empty($categorySpentId)) {
            $this->Session->setFlash('Please chose a transaction.');
            return;
        } else {
            $this->Session->setFlash('Only allowed to choose one of two transactions.');
            return;
        }

        //save data
        $transaction = $this->Transaction->add($data, $walletId, $categoryId);
        if ($transaction) {
            $this->Session->setFlash('Transaction has been saved.');
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        } else {
            $this->Session->setFlash('Error. Please try again.');
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        }
    }

    /**
     * Edit transaction
     * 
     * @param int $transactionId
     */
    public function edit($transactionId = 0)
    {
        //get userId
        $userId = $this->Auth->user('id');

        //get list name category of user
        $this->loadModel('Category');
        $getListNameCategorySpent  = $this->Category->getListNameCategorySpent($userId);
        $getListNameCategoryEarned = $this->Category->getListNameCategoryEarned($userId);
        $this->set('listCategorySpent', $getListNameCategorySpent);
        $this->set('listCategoryEarned', $getListNameCategoryEarned);

        //get current walletId
        $this->loadModel('User');
        $data     = $this->User->findUserById($userId);
        $walletId = $data['User']['current_wallet_id'];

        //check transaction belong to wallet
        $checkWalletTransaction = $this->Transaction->checkWalletTransaction($walletId, $transactionId);
        if (empty($checkWalletTransaction)) {
            $this->Session->setFlash('Not allow');
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        }

        //check request
        if (!$this->request->is(array('put', 'post'))) {
            return;
        }

        //get data
        //get categoryId
        $data             = $this->request->data['Transaction'];
        $categorySpentId  = $this->request->data['Transaction']['categorySpentId'];
        $categoryEarnedId = $this->request->data['Transaction']['categoryEarnedId'];
        $amount           = $this->request->data['Transaction']['amount'];

        if (empty($categorySpentId) && !empty($categoryEarnedId)) {
            $categoryId = $categoryEarnedId;
        } elseif (empty($categoryEarnedId) && !empty($categorySpentId)) {
            $categoryId = $categorySpentId;
        } elseif (empty($categoryEarnedId) && empty($categorySpentId)) {
            $this->Session->setFlash('Please chose a transaction.');
            return;
        } else {
            $this->Session->setFlash('Only allowed to choose one of two transactions.');
            return;
        }

        //save edited transaction
        $oldData     = $this->Transaction->findById($transactionId);
        $this->loadModel('Category');
        $newData     = $this->Category->findById($categoryId);
        $transaction = $this->Transaction->edit($data, $categoryId, $transactionId);
        if ($transaction) {
            $this->Session->setFlash('Transaction has been saved.');
        } else {
            $this->Session->setFlash('Error. Please try again');
        }

        //save balance in current wallet
        if ($oldData['Category']['purpose'] == false && $newData['Category']['purpose'] == false) {
            $this->loadModel('Wallet');
            $amount = ($oldData['Transaction']['amount'] - $amount);
            $this->Wallet->transactionMoney($walletId, $amount);
        }
        if ($oldData['Category']['purpose'] == true && $newData['Category']['purpose'] == true) {
            $this->loadModel('Wallet');
            $amount = ($amount - $oldData['Transaction']['amount']);
            $this->Wallet->transactionMoney($walletId, $amount);
        }
        if ($oldData['Category']['purpose'] == false && $newData['Category']['purpose'] == true) {
            $this->loadModel('Wallet');
            $amount = ($amount + $oldData['Transaction']['amount']);
            $this->Wallet->transactionMoney($walletId, $amount);
        }
        if ($oldData['Category']['purpose'] == true && $newData['Category']['purpose'] == false) {
            $this->loadModel('Wallet');
            $amount = -($amount + $oldData['Transaction']['amount']);
            $this->Wallet->transactionMoney($walletId, $amount);
        }
        $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
    }

    /**
     * delete transaction by transactionId
     * 
     * @param int $transactionId
     */
    public function delete($transactionId)
    {
        //get userId
        $userId = $this->Auth->user('id');

        //get current walletId
        $this->loadModel('User');
        $data     = $this->User->findUserById($userId);
        $walletId = $data['User']['current_wallet_id'];

        //check transaction belong to wallet
        $checkWalletTransaction = $this->Transaction->checkWalletTransaction($walletId, $transactionId);
        if (empty($checkWalletTransaction)) {
            $this->Session->setFlash('Not allow');
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        }

        //get amount to save before delete
        $data = $this->Transaction->findById($transactionId);
        if ($data['Category']['purpose'] == false) {
            $amount = $data['Transaction']['amount'];
        } else {
            $amount = -$data['Transaction']['amount'];
        }

        //save balance to the current wallet
        $this->loadModel('Wallet');
        $this->Wallet->transactionMoney($walletId, $amount);
        $delete = $this->Transaction->delete($transactionId);
        if ($delete) {
            $this->Session->setFlash('Transaction has been deleted.');
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        } else {
            $this->Session->setFlash('Error. Please try again.');
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        }
    }

    //TODO
    public function statistic()
    {
        //get userId
        $userId = $this->Auth->user('id');

        //get default wallet
        $this->loadModel('User');
        $data         = $this->User->findUserById($userId);
        var_dump($data['Wallet']['balance']);
        $walletId     = $data['User']['current_wallet_id'];
        $transactions = $this->Transaction->getListTransactionsByWalletId($walletId);
        
        foreach ($transactions as $transaction) {
            if ($transaction['Category']['purpose'] == false) {
                $this->expense += $transaction['Transaction']['amount'];
            } else {
                $this->income += $transaction['Transaction']['amount'];
            }
        }
        $this->set('expense', $this->expense);
        $this->set('income', $this->income);
        $this->set('currentMoney', $data['Wallet']['balance']);
    }

}

?>
