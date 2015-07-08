<?php

class WalletsController extends AppController
{

    // TODO With Category
    public function index()
    {
        
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
        $data = $this->request->data['Wallet'];
        $idu  = $this->Auth->user('id');

        //add wallet
        $add = $this->Wallet->add($data, $idu);
        if ($add) {
            $this->Session->setFlash('Wallet has been saved.');
            $this->redirect(array('action' => 'view'));
        } else {
            $this->Session->setFlash('Wallet cound not be saved. Please try again.');
        }
    }

    /**
     *  Delete wallet
     * 
     * @param int $idw
     */
    public function delete($idw = 0)
    {
        //check params
        if (empty($this->request->params['pass']['0'])) {
            throw new ErrorException();
        }
        $idw = $this->request->params['pass'][0];

        //get userId
        $idu = $this->Auth->user('id');

        // check wallet belongs user
        $checkUserWallet = $this->Wallet->checkUserWallet($idu, $idw);

        //edit wallet
        if ($checkUserWallet) {
            if ($this->Wallet->delete($idw)) {
                $this->Session->setFlash('Wallet deleted');
                $this->redirect(array('action' => 'view'));
            } else {
                $this->Session->setFlash('Wallet was not deleted. Please try again.');
            }
        } else {
            $this->Session->setFlash('You do not have permission to access.');
            $this->redirect(array('action' => 'view'));
        }
    }

    /**
     *  Edit wallet
     * 
     * @param int $idw
     */
    public function edit($idw = 0)
    {
        //check params
        if (empty($this->request->params['pass']['0'])) {
            throw new ErrorException();
        }
        $idw = $this->request->params['pass'][0];

        //get userId
        $idu = $this->Auth->user('id');

        // check wallet belongs user
        $checkUserWallet = $this->Wallet->checkUserWallet($idu, $idw);

        //edit wallet
        if ($checkUserWallet) {
            //check request
            if (!$this->request->is(array('post', 'put'))) {
                return;
            }
            $data = $this->request->data['Wallet'];
            $edit = $this->Wallet->edit($data, $idw);
            if ($edit) {
                $this->Session->setFlash('Wallet has been saved.');
                $this->redirect(array('action' => 'view'));
            }
        } else {
            $this->Session->setFlash('You do not have permission to access.');
            $this->redirect(array('action' => 'view'));
        }
    }

    /**
     *  Transfer money wallets
     * 
     */
    public function transfer()
    {
        //get list wallets
        $idu  = $this->Auth->user('id');
        $list = $this->Wallet->findWallet($idu);

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
                $this->redirect(array('action' => 'view'));
            } else {
                $this->Session->setFlash('Error. Please try again.');
            }
        }
    }

}
