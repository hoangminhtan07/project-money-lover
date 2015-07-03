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

    public function delete()
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $id = $this->Auth->user('id');
        if ($this->Wallet->delete($id)) {
            $this->Session->setFlash('Wallet deleted');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('Wallet was not deleted');
        }
        $this->redirect(array('action' => 'index'));
    }

    public function edit($idw = 0)
    {
        $idu = $this->Auth->user('id');
        if ($this->checkUserWallet($idu, $idw)) {
            if ($this->request->is(array('post', 'put'))) {
                $data = $this->request->data['Wallet'];
                if ($this->Wallet->edit($data)) {
                    $this->Session->setFlash('Wallet has been saved.');
                    $this->redirect(array('action' => 'view'));
                }
            }
        } else {
            $this->Session->setFlash('You must not access.');
            $this->redirect(array('action' => 'view'));
        }
    }

}
