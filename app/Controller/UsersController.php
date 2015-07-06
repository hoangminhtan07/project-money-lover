<?php

//App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class UsersController extends AppController
{

    public $name    = 'Users';
    public $helpers = array('Html', 'Form');

    public function index()
    {
        $id = $this->Auth->user('id');
        $this->set('user', $this->User->findById($id));
    }

    public function login()
    {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash('Your username/password combination was incorrect');
            }
        }
    }

    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('add');
    }

    public function change_password()
    {
        if ($this->request->is(array('post', 'put'))) {
            $id   = $this->Auth->user('id');
            $data = $this->request->data['User'];
            if ($this->User->edit($data, $id)) {
                $this->Session->setFlash('The password has been changed.');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The password cound not be change. Please try again.');
            }
        } else {
            $this->request->data = $this->User->read();
        }
    }

    public function verify() //function check user email.
    {
        
    }

    function generateRandomString($length = 10) //function generateRandomString
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $data                  = $this->request->data['User'];
            $uemail                = $this->request->data['User']['email'];
            $randomString          = $this->generateRandomString(40);
            if ($this->User->add($data,$randomString)) {
                $this->send_email($uemail);
                $this->Session->setFlash('The user has been saved. Please check your email to verify.');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The user cound not be saved. Please try again.');
            }
        }
    }

    public function send_email($uemail = null)
    {
        $Email = new CakeEmail('gmail');
        $Email->from(array('moneylover1909@gmail.com' => 'money server'));
        $Email->to($uemail);
        $Email->subject('Verify email from Money.server.dev');
        $Email->template('default');
        $Email->send();
    }

    public function edit()
    {
        if ($this->request->is(array('post', 'put'))) {
            $id   = $this->Auth->user('id');
            $data = $this->request->data['User'];
            if ($this->User->edit($data, $id)) {
                $this->Session->setFlash('The user has been saved');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The user cound not be saved. Please try again.');
            }
        } else {
            $this->request->data = $this->User->read();
        }
    }

    public function delete()
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $id = $this->Auth->User('id');
        if ($this->User->delete($id)) {
            $this->Session->setFlash('User deleted');
            $this->redirect($this->Auth->logout());
        } else {
            $this->Session->setFlash('User was not deleted');
        }
        $this->redirect(array('action' => 'index'));
    }

    public function set_current($idw = 0)
    {
        $id = $this->Auth->user('id');
        if ($this->User->set_current($id, $idw)) {
            $this->Session->setFlash('Current wallet has been changed.');
            $this->redirect(array('controller' => 'wallets', 'action' => 'view'));
        } else {
            $this->Session->setFlash('Error. Please try again.');
        }
    }

}
