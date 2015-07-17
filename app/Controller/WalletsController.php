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

        //get current wallet
        $this->loadModel('User');
        $walletId = $this->User->getCurrentWalletIdByUserId($userId);

        if (empty($walletId)) {
            $this->Session->setFlash('You have not current wallet yet. Please set current wallet.');
            $this->redirect(array('action' => 'view'));
        }

        //get wallet by walletId
        $wallet = $this->Wallet->getWalletById($walletId);

        //set view
        $this->set('wallet', $wallet);

        //get list transactions bind category by walletId
        $this->loadModel('Transaction');
        $this->Transaction->bindCategory();
        $this->Transaction->bindWallet();
        $transactions = $this->Transaction->getListTransactionsByWalletId($walletId);

        //get list transaction bind category order by category name
        if ($order == 'Order_by_Category') {
            $this->loadModel('Transaction');
            $this->Transaction->bindCategory();
            $this->Transaction->bindWallet();
            $transactions = $this->Transaction->getListTransactionsOrderByCategoriesName($walletId);
        }

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
            //delete all transactions by walletId
            $this->loadModel('Transaction');
            $del = $this->Transaction->deleteTransactionsByWalletId($walletId);
            if ($del) {

                //delete wallet apter delete all transactions
                $del = $this->Wallet->deleteWalletById($walletId);
                if ($del) {

                    //set null current user wallet id
                    $this->loadModel('User');
                    $this->User->setCurrentWallet($userId, null);

                    $this->Session->setFlash('Wallet deleted.');
                } else {
                    $this->Session->setFlash('Wallet was not deleted. Please try again.');
                }
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

            //get data request
            $data = $this->request->data['Wallet'];

            //save edit data
            $edit = $this->Wallet->edit($data, $walletId);
            if ($edit) {
                $this->Session->setFlash('Wallet has been saved.');
            } else {
                $this->Session->setFlash('Wallet was not saved. Please try again.');
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
        $list   = $this->Wallet->getListWalletsNameByUserId($userId);

        //check list wallets
        if (empty($list)) {
            $this->Session->setFlash('You have not wallet yet.');
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
