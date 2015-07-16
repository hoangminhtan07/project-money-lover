<?php

class WalletsController extends AppController
{

    /**
     * view all transaction in current wallet
     * 
     * @param string $order
     */
    public function index($order = null)
    {
//get userId
        $userId = $this->Auth->user('id');

//get default wallet
        $this->loadModel('User');
        $data     = $this->User->getUserById($userId);
        $walletId = $data['User']['current_wallet_id'];
        if (empty($walletId)) {
            $this->Session->setFlash('You have not deffault wallet yet. Please set deffault wallet.');
            $this->redirect(array('action' => 'view'));
        }
        $wallet = $this->Wallet->getWalletById($walletId);
        $this->set('wallet', $wallet);

//set all transaction view
        $this->loadModel('Transaction');
        $transactions = $this->Transaction->getListTransactionsByWalletId($walletId);

        if ($order == 'Order_by_Category') {
            $this->loadModel('Transaction');
            $transactions = $this->Transaction->getListTransactionsOrderByCategoriesName($walletId);
        }
        $this->set('transactions', $transactions);
    }

    /**
     * View all wallet belong to user
     */
    public function view()
    {
//get userId
        $id = $this->Auth->user('id');

//find all wallet
        $data = $this->Wallet->view($id);
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
            $this->Session->setFlash('Wallet has been saved.');
        } else {
            $this->Session->setFlash('Wallet cound not be saved. Please try again.');
        }
        $this->redirect(array('action' => 'view'));
    }

    /**
     *  Delete wallet
     * 
     * @param int $wallet
     */
    public function delete($walletId)
    {
//check params
        if (empty($walletId)) {
            throw new ErrorException();
        }

//get userId
        $userId = $this->Auth->user('id');

// check wallet belongs user
        $checkUserWallet = $this->Wallet->checkUserWallet($userId, $walletId);

//delete wallet
        if ($checkUserWallet) {
            if ($this->Wallet->delete($walletId)) {
                $this->Session->setFlash('Wallet deleted');
            } else {
                $this->Session->setFlash('Wallet was not deleted. Please try again.');
            }
        } else {
            $this->Session->setFlash('You do not have permission to access.');
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
        if ($checkUserWallet) {
//check request
            if (!$this->request->is(array('post', 'put'))) {
                return;
            }
            $data = $this->request->data['Wallet'];
            $edit = $this->Wallet->edit($data, $walletId);
            if ($edit) {
                $this->Session->setFlash('Wallet has been saved.');
            }
        } else {
            $this->Session->setFlash('You do not have permission to access.');
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
        $list   = $this->Wallet->getWalletByUserId($userId);

//check list wallets
        if (empty($list)) {
            $this->Session->setFlash('You have not wallet yet.');
            $this->redirect(array('action' => 'view'));
        }

//set View
        $this->set('list', $list);

//get data
        if (!$this->request->is('post')) {
            return;
        }
        $data = $this->request->data;

//check fromWalletId vs toWalletId
        $fromWalletId = $data['Wallet']['fromWallet'];
        $toWalletId   = $data['Wallet']['toWallet'];
        if ($fromWalletId == $toWalletId) {
            $this->Session->setFlash('Wallets must be different.');
        } else {
            $amounts = $data['Wallet']['amounts'];

//update wallet balance
            $newBalance = $this->Wallet->transfer($fromWalletId, $toWalletId, $amounts);
            if ($newBalance) {
                $this->Session->setFlash('Balance has been update');
            } else {
                $this->Session->setFlash('Error. Please try again.');
            }
            $this->redirect(array('action' => 'view'));
        }
    }

}

