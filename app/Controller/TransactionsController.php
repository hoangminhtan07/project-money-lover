<?php

class TransactionsController extends AppController
{

    private $income  = 0;
    private $expense = 0;
    public $uses     = array('Transaction', 'User', 'Category', 'Wallet');

    const earned = 1;
    const spent  = 0;

    /**
     *   Add transaction
     */
    public function add()
    {

        //get userId
        $userId = $this->Auth->user('id');

        //get list name category of user
        $getListNameCategorySpent  = $this->Category->getListNameCategoryByPurpose($userId, self::spent);
        $getListNameCategoryEarned = $this->Category->getListNameCategoryByPurpose($userId, self::earned);
        $this->set('listCategorySpent', $getListNameCategorySpent);
        $this->set('listCategoryEarned', $getListNameCategoryEarned);

        //get current walletId
        $walletId = $this->User->getCurrentWalletIdByUserId($userId);

        //check request
        if (!$this->request->is('post')) {
            return;
        }

        // Validate inputs
        $this->Transaction->set($this->request->data);
        $valid = $this->Transaction->validates();
        if (!$valid) {
            return;
        }

        //get request data 
        //get categoryId
        $data             = $this->request->data['Transaction'];
        $categorySpentId  = $data['categorySpentId'];
        $categoryEarnedId = $data['categoryEarnedId'];
        $amount           = $data['amount'];
        if (empty($data['created'])) {
            $data['created'] = date('Y-m-d H:i:s');
        } else {
            $data['created'] = str_replace('-', '/', $data['created']);
            $data['created'] = date('Y-m-d', strtotime($data['created']));
        }

        if (empty($categorySpentId) && !empty($categoryEarnedId)) {
            $categoryId = $categoryEarnedId;
        } elseif (empty($categoryEarnedId) && !empty($categorySpentId)) {
            $categoryId = $categorySpentId;
            $amount     = -$amount;
        } elseif (empty($categoryEarnedId) && empty($categorySpentId)) {
            $this->Session->setFlash(__('Please chose a transaction.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        } else {
            $this->Session->setFlash(__('Only allowed to choose one of two transactions.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        }

        //check category belong to user
        $checkCategoryUser = $this->Category->checkUserCategory($userId, $categoryId);
        if (!$checkCategoryUser) {
            $this->Session->setFlash(__('Error, try again.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        }

        $data['category_id'] = $categoryId;
        $data['wallet_id']   = $walletId;

        //save data
        $transaction = $this->Transaction->add($data);
        if ($transaction) {

            //update balance to default wallet
            $this->Wallet->transactionMoney($walletId, $amount);
            $this->Session->setFlash(__('Transaction has been saved.'), 'alert_box', array('class' => 'alert-success'));
        } else {
            $this->Session->setFlash(__('Error. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        }
        $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
    }

    /**
     * Edit transaction
     * 
     * @param int $transactionId
     */
    public function edit($transactionId)
    {
        //check params
        if (empty($transactionId)) {
            throw new ErrorException();
        }

        //get userId
        $userId = $this->Auth->user('id');

        //get list name category of user
        $getListNameCategorySpent  = $this->Category->getListNameCategoryByPurpose($userId, self::spent);
        $getListNameCategoryEarned = $this->Category->getListNameCategoryByPurpose($userId, self::earned);
        $this->set('listCategorySpent', $getListNameCategorySpent);
        $this->set('listCategoryEarned', $getListNameCategoryEarned);

        //get current walletId
        $walletId = $this->User->getCurrentWalletIdByUserId($userId);

        //check transaction belong to wallet
        $checkWalletTransaction = $this->Transaction->checkWalletTransaction($walletId, $transactionId);
        if (empty($checkWalletTransaction)) {
            $this->Session->setFlash(__('Not allow'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        }

        $data = $this->Transaction->getTransactionById($transactionId);
        if (empty($this->request->data)) {
            $this->request->data = $data;
        }

        //check request
        if (!$this->request->is(array('put', 'post'))) {
            return;
        }

        // Validate inputs
        $this->Transaction->set($this->request->data);
        $valid = $this->Transaction->validates();
        if (!$valid) {
            return;
        }

        //get data
        //get categoryId
        $data             = $this->request->data['Transaction'];
        $categorySpentId  = $data['categorySpentId'];
        $categoryEarnedId = $data['categoryEarnedId'];
        $amount           = $data['amount'];

        if (empty($categorySpentId) && !empty($categoryEarnedId)) {
            $categoryId = $categoryEarnedId;
        } elseif (empty($categoryEarnedId) && !empty($categorySpentId)) {
            $categoryId = $categorySpentId;
            $amount     = -$amount;
        } elseif (empty($categoryEarnedId) && empty($categorySpentId)) {
            $this->Session->setFlash(__('Please chose a transaction.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        } else {
            $this->Session->setFlash(__('Only allowed to choose one of two transactions.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        }

        $this->Transaction->bindCategory();
        $oldData = $this->Transaction->getTransactionById($transactionId);

        $data['category_id'] = $categoryId;
        $data['amount']      = $amount;

        //save edited transaction
        $transaction = $this->Transaction->edit($data, $transactionId);
        if (!$transaction) {
            $this->Session->setFlash(__('Error. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        }

        //save balance in current wallet
        $amount = $amount - $oldData['Transaction']['amount'];
        $this->Wallet->transactionMoney($walletId, $amount);

        $this->Session->setFlash(__('Transaction has been saved.'), 'alert_box', array('class' => 'alert-success'));
        $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
    }

    /**
     * delete transaction by transactionId
     * 
     * @param int $transactionId
     */
    public function delete($transactionId)
    {
        //check request
        if (!$this->request->is('post', 'put')) {
            throw new BadRequestException('Bad request');
        }

        //check params
        if (empty($transactionId)) {
            throw new ErrorException();
        }
        //get userId
        $userId = $this->Auth->user('id');

        //get current walletId
        $data     = $this->User->getUserById($userId);
        $walletId = $data['User']['current_wallet_id'];

        //check transaction belong to wallet
        $checkWalletTransaction = $this->Transaction->checkWalletTransaction($walletId, $transactionId);
        if (empty($checkWalletTransaction)) {
            $this->Session->setFlash(__('Not allow.'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        }

        //get amount to save before delete
        $data   = $this->Transaction->getTransactionById($transactionId);
        $amount = -$data['Transaction']['amount'];

        $del = $this->Transaction->deleteTransactionById($transactionId);
        if ($del) {
            $this->Session->setFlash(__('Transaction has been deleted.'), 'alert_box', array('class' => 'alert-success'));

            //save balance to the current wallet
            $this->Wallet->transactionMoney($walletId, $amount);
        } else {
            $this->Session->setFlash(__('Error. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        }
        $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
    }

    /**
     *  Statistic current wallet
     * 
     */
    public function statistic()
    {
        //get userId
        $userId = $this->Auth->user('id');

        //get current walletId
        $walletId = $this->User->getCurrentWalletIdByUserId($userId);

        //get balance of an wallet by walletId
        $currentMoney = $this->Wallet->getBalanceByWalletId($walletId);

        //get list transactions bidWallet bind Category by wallet Id
        $this->Transaction->bindCategory();
        $transactions = $this->Transaction->getListTransactionsByWalletId($walletId);

        //calculate expense and income money
        foreach ($transactions as $transaction) {
            if ($transaction['Category']['purpose'] == false) {
                $this->expense += $transaction['Transaction']['amount'];
            } else {
                $this->income += $transaction['Transaction']['amount'];
            }
        }

        //set view
        $this->set(array(
            'expense'      => $this->expense,
            'income'       => $this->income,
            'currentMoney' => $currentMoney,
        ));

        //check request
        if (!$this->request->is(array('put', 'post'))) {
            return;
        }

        //get data request

        $data = $this->request->data['Static'];

        $fdate = str_replace('-', '/', $data['fdate']);
        $fdate = date('Y-m-d', strtotime($fdate));

        $tdate = str_replace('-', '/', $data['tdate']);
        $tdate = date('Y-m-d', strtotime($tdate . "+1 dates"));

        //get list cates to display month report
        $cates = $this->cateArrangement($transactions, $fdate, $tdate);

        //calculate sumIncome and sumExpense formMonth toMonth
        $sumIncome  = 0;
        $sumExpense = 0;
        foreach ($cates as $detail) {
            if ($detail['purpose'] == true) {
                $sumIncome += $detail['amount'];
            } else {
                $sumExpense += $detail['amount'];
            }
        }

        //set view
        $this->set(array(
            'cates'      => $cates,
            'sumIncome'  => $sumIncome,
            'sumExpense' => $sumExpense,
        ));
    }

    /**
     *  Generate new cates array to display report
     * 
     * @param array $trans
     * @param string $fdate
     * @param string $tdate
     * @return array
     */
    private function cateArrangement($trans, $fdate, $tdate)
    {
        $newCates = array();
        foreach ($trans as $key => $value) {
            $date = date('Y-m-d', strtotime($value['Transaction']['created']));
            if ($fdate <= $date && $date <= $tdate) {
                $cateId = $value['Category']['id'];
                if (!array_key_exists($cateId, $newCates)) {
                    $amount = 0;
                    foreach ($trans as $key => $value) {
                        if ($cateId == $value['Category']['id']) {
                            $newCates[$cateId]['name']    = $value['Category']['name'];
                            $newCates[$cateId]['purpose'] = $value['Category']['purpose'];
                            $amount += $value['Transaction']['amount'];
                            $newCates[$cateId]['amount']  = $amount;
                        }
                    }
                }
            }
        }
        return $newCates;
    }

}
