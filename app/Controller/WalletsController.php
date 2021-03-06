<?php

class WalletsController extends AppController
{

    public $uses       = array('Wallet', 'User', 'Transaction', 'Category');
    public $components = array('Paginator');

    /**
     *  View all transactions on user's current wallet
     * 
     */
    public function index()
    {

        //get userId
        $userId = $this->Auth->user('id');

        //get current wallet
        $walletId = $this->User->getCurrentWalletIdByUserId($userId);

        if (empty($walletId)) {
            $this->Session->setFlash(__('You have not current wallet yet. Please set current wallet.'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('action' => 'view'));
        }

        //get wallet by walletId
        $wallet = $this->Wallet->getWalletById($walletId);

        //set view
        $this->set('wallet', $wallet);

        $this->paginate = array(
            'conditions' => array(
                'wallet_id' => $walletId,
            ),
            'limit'      => 3,
        );

        //get list transactions bind category by walletId
        $this->Transaction->bindCategory();
        $transactions = $this->paginate('Transaction');
        //$this->set('transactions', $transactions);
        //set view
        $this->set('transactions', $transactions);
    }

    /**
     * View Transactions sort by date
     * 
     * @param string $nameCateId
     * @param string $fdate
     * @param string $tdate
     */
    public function viewByDateRange($nameCateId, $fdate = null, $tdate = null)
    {
        //set view form date and to date
        $this->set(array(
            'fdate' => $fdate,
            'tdate' => $tdate,
        ));

        //set default fdate, tdate
        if (empty($fdate) || ($fdate == 'NaN-NaN-NaN')) {
            $fdate = '0000-00-00';
        }
        if (empty($tdate) || ($tdate == 'NaN-NaN-NaN')) {
            $tdate = '9999-12-30';
        }

        $tdate = str_replace('-', '/', $tdate);
        $tdate = date('Y-m-d', strtotime($tdate . "+1 days"));

        //get userId
        $userId = $this->Auth->user('id');

        //get current wallet
        $walletId = $this->User->getCurrentWalletIdByUserId($userId);

        //format $nameCateId to get categoryId
        $categoryId = substr($nameCateId, 5, strlen($nameCateId));

        //get list name category has transactions with current wallet
        $this->Transaction->bindCategory();
        $this->Transaction->bindWallet();
        $data             = $this->Transaction->getListTransactionsByWalletId($walletId);
        $listCateOfWallet = $this->generateListNameCateOfWallet($data);

        //get list transactions bind category to show by date from fdate to tdate
        $this->Transaction->bindCategory();
        $transByDate = $this->Transaction->getlistTransactionsByDate($walletId, $categoryId, $fdate, $tdate);

        //set view
        $this->set(array(
            'listCateOfWallet' => $listCateOfWallet,
            'transByDate'      => $transByDate,
        ));
    }

    /**
     * View all wallet belong to user
     */
    public function view()
    {
        //get userId
        $id = $this->Auth->user('id');

        //get all wallets by userId
        $data = $this->Wallet->getWalletsByUserId($id);

        //set view
        $this->set('wallets', $data);
    }

    /**
     *  Add wallet
     */
    public function add()
    {
        //check request
        if (!$this->request->is('post')) {
            return;
        }
        //get data
        $data   = $this->request->data['Wallet'];
        $userId = $this->Auth->user('id');

        //add wallet
        $add = $this->Wallet->add($data, $userId);
        if ($add) {
            $this->Session->setFlash(__('Wallet has been saved.'), 'alert_box', array('class' => 'alert-success'));
        } else {
            $this->Session->setFlash(__('Wallet cound not be saved. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        }
        $this->redirect(array('action' => 'view'));
    }

    /**
     *  Delete wallet
     * 
     * @param int $walletId
     */
    public function delete($walletId)
    {
        //check request
        if (!$this->request->is('post', 'put')) {
            throw new BadRequestException('Bad request');
        }

        //check params
        if (empty($walletId)) {
            throw new ErrorException();
        }

        //get userId
        $userId = $this->Auth->user('id');

        //get current walletId by userId
        $currentWalletId = $this->User->getCurrentWalletIdByUserId($userId);

        // check wallet belongs user
        $checkUserWallet = $this->Wallet->checkUserWallet($userId, $walletId);

        //delete wallet
        if (!$checkUserWallet) {
            $this->Session->setFlash(__('You do not have permission to access.'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('action' => 'view'));
        }
        //delete all transactions by walletId
        $del = $this->Wallet->deleteWalletById($walletId);
        if ($del) {
            //set null current user walletId if currentWallet was deleted
            if ($currentWalletId == $walletId) {
                $this->User->setCurrentWallet($userId, null);
            }
            $this->Session->setFlash(__('Wallet deleted.'), 'alert_box', array('class' => 'alert-success'));
            $this->redirect(array('action' => 'view'));
        } else {
            $this->Session->setFlash(__('Wallet was not deleted. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
    }

    /**
     *  Edit wallet
     * 
     * @param int $walletId
     */
    public function edit($walletId)
    {
        //check params
        if (empty($walletId)) {
            throw new ErrorException();
        }

        //get userId
        $userId = $this->Auth->user('id');

        // check wallet belongs user
        $checkUserWallet = $this->Wallet->checkUserWallet($userId, $walletId);

        //edit wallet
        if (!$checkUserWallet) {
            $this->Session->setFlash(__('You do not have permission to access.'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('action' => 'view'));
        }

        $data = $this->Wallet->getWalletById($walletId);
        if (empty($this->request->data)) {
            $this->request->data = $data;
        }

        //check request
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }

        //get data request
        $data = $this->request->data['Wallet'];

        //save edit data
        $edit = $this->Wallet->edit($data, $walletId);
        if ($edit) {
            $this->Session->setFlash(__('Wallet has been saved.'), 'alert_box', array('class' => 'alert-success'));
            $this->redirect(array('action' => 'view'));
        } else {
            $this->Session->setFlash(__('Wallet was not saved. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
    }

    /**
     *  Transfers between wallets
     * 
     */
    public function transfer()
    {
        //get list wallets
        $userId = $this->Auth->user('id');
        $list   = $this->Wallet->getListWalletsNameByUserId($userId);

        //check list wallets
        if (empty($list)) {
            $this->Session->setFlash(__('You have not wallet yet.'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('action' => 'view'));
        }

        //set view
        $this->set('list', $list);

        //check request
        if (!$this->request->is('post')) {
            return;
        }

        //get data request
        $data = $this->request->data;

        //check fromWalletId vs toWalletId
        $fromWalletId = $data['Wallet']['fromWallet'];

        //check fromWallet belongto current user 
        $checkUserWallet = $this->Wallet->checkUserWallet($userId, $fromWalletId);
        if (!$checkUserWallet) {
            $this->Session->setFlash(__('Access Denied'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('action' => 'index'));
        }
        $toWalletId = $data['Wallet']['toWallet'];

        //check toWallet belongto current user
        $checkUserWallet = $this->Wallet->checkUserWallet($userId, $toWalletId);
        if (!$checkUserWallet) {
            $this->Session->setFlash(__('Access Denied'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('action' => 'index'));
        }

        if ($fromWalletId == $toWalletId) {
            $this->Session->setFlash(__('Wallets must be different.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        }
        $amounts = $data['Wallet']['amounts'];

        //update wallet balance
        $newBalance = $this->Wallet->transfer($fromWalletId, $toWalletId, $amounts);
        if ($newBalance) {
            $this->Session->setFlash(__('Balance has been update'), 'alert_box', array('class' => 'alert-success'));
            $this->redirect(array('action' => 'view'));
        } else {
            $this->Session->setFlash(__('Error. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
    }

    //generate list name categories of wallet
    private function generateListNameCateOfWallet($trans)
    {
        $newCates = array();
        foreach ($trans as $key => $value) {
            $cateId = $value['Category']['id'];
            if (!array_key_exists($cateId, $newCates)) {
                $newCates[$cateId] = array($value['Category']['name'], $value['Category']['purpose']);
            }
        }
        return $newCates;
    }

}
