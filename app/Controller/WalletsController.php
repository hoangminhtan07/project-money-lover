<?php

class WalletsController extends AppController
{

    public $uses       = array('Wallet', 'User', 'Transaction');
    public $components = array('Paginator');

    /**
     * view all transaction in current wallet
     * 
     */
    public $paginate = array(
        'limit' => 3,
    );

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

        //get list transactions bind category by walletId
        $this->Transaction->bindCategory();
        $this->Transaction->bindWallet();
        $transactions = $this->Transaction->getListTransactionsByWalletId($walletId);

        //set view
        $this->set('transactions', $transactions);
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
        } else {
            $this->Session->setFlash(__('Wallet was not deleted. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }

        $this->redirect(array('action' => 'view'));
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
        } else {
            $this->Session->setFlash(__('Wallet was not saved. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
        $this->redirect(array('action' => 'view'));
    }

    /**
     *  Transfer money wallets
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
        } else {
            $this->Session->setFlash(__('Error. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
        $this->redirect(array('action' => 'view'));
    }

    /**
     * view transaction by date range
     * 
     * @param int $walletId
     */
    public function viewDay($walletId)
    {

        //get userId
        $userId = $this->Auth->user('id');

        //check Wallet belongsTo user
        $checkUserWallet = $this->Wallet->checkUserWallet($userId, $walletId);
        if (!$checkUserWallet) {
            $this->Session->setFlash(__('Can not access.'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        }

        //get list transactions bind Category by walletId
        $this->Transaction->bindCategory();
        $oldListTrans              = $this->Transaction->getListTransactionsByWalletId($walletId);
        $this->Paginator->settings = $this->paginate;
        $this->Transaction->bindCategory();
        $old                       = $this->Paginator->paginate('Transaction', array(
            'Transaction.wallet_id' => $walletId,
        ));
        $newListTrans              = $this->getListTransactionsOrderByDateRange($oldListTrans);
        $this->set('transactions', $newListTrans);
        $this->set('trans', $old);
    }

    /**
     * generate new array transaction pointing by date
     * 
     * @param array $trans
     * @return array
     */
    private function getListTransactionsOrderByDateRange($trans)
    {
        $reqs = array();
        foreach ($trans as $date) {
            $createTime = date('Y-m-d', strtotime($date['Transaction']['created']));
            if (!array_key_exists($createTime, $reqs)) {
                foreach ($trans as $value) {
                    if ($createTime == date('Y-m-d', strtotime($value['Transaction']['created']))) {
                        $reqs[$createTime][] = $value;
                    }
                }
            }
        }
        return $reqs;
    }

    /**
     *  view transaction sort by categoryName
     * 
     * @param int $walletId
     */
    public function viewCategory($walletId)
    {
        //get userId
        $userId = $this->Auth->user('id');

        //check Wallet belongsTo user
        $checkUserWallet = $this->Wallet->checkUserWallet($userId, $walletId);
        if (!$checkUserWallet) {
            $this->Session->setFlash(__('Can not access.'), 'alert_box', array('class' => 'alert-danger'));
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        }

        //get list transactions bind Category by walletId
        $this->Transaction->bindCategory();
        $oldListTrans = $this->Transaction->getListTransactionsByWalletId($walletId);
        $newListTrans = $this->getListTransactionsOrderByCategory($oldListTrans);
        $this->set('transactions', $newListTrans);
    }

    /**
     * generate new array transaction pointing by categoryName
     * 
     * @param array $trans
     * @return array
     */
    private function getListTransactionsOrderByCategory($trans)
    {
        $reqs = array();
        foreach ($trans as $name) {
            $categoryName = $name['Category']['name'];
            if (!array_key_exists($categoryName, $reqs)) {
                foreach ($trans as $value) {
                    if ($categoryName === $value['Category']['name']) {
                        $reqs[$categoryName][] = $value;
                    }
                }
            }
        }
        return $reqs;
    }

}
