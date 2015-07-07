<?php

class WalletsController extends AppController
{

    public function index()
    {
        
    }

    public function view()
    {
        $id   = $this->Auth->user('id');
        $data = $this->Wallet->view($id);
        $this->set('wallets', $data);
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $data = $this->request->data['Wallet'];
            $idu  = $this->Auth->user('id');
            if ($this->Wallet->add($data, $idu)) {
                $this->Session->setFlash('Wallet has been saved.');
                $this->redirect(array('action' => 'view'));
            } else {
                $this->Session->setFlash('Wallet cound not be saved. Please try again.');
            }
        }
    }

    public function delete($idw = 0)
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $idu        = $this->Auth->user('id');
        $numResults = $this->Wallet->find('count', array('conditions' => array('Wallet.user_id' => $idu, 'Wallet.id' => $idw)));
        if ($numResults > 0) {  // check wallet belongs user
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

    public function edit($idw = 0)
    {
        $idu        = $this->Auth->user('id');
        $idw = $this->request->farams['pass']['0'];
        if (checkUserWallet) {  // check wallet belongs user
            if ($this->request->is(array('post', 'put'))) {
                $data = $this->request->data['Wallet'];
                if ($this->Wallet->edit($data, $idw)) {
                    $this->Session->setFlash('Wallet has been saved.');
                    $this->redirect(array('action' => 'view'));
                }
            }
        } else {
            $this->Session->setFlash('You do not have permission to access.');
            $this->redirect(array('action' => 'view'));
        }
    }

}
