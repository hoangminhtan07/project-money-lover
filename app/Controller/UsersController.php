<?php

class UsersController extends AppController
{

    public $uses    = array('User');
    public $helpers = array('Html', 'Form');

    public function index()
    {
        $id = $this->Auth->user('id');
        $this->set('user', $this->User->findById($id));
    }

    /**
     * function user login
     */
    public function login()
    {
        //check request METHOD
        if (!$this->request->is('post')) {
            return;
        }
        if ($this->Auth->login()) {
            $this->redirect($this->Auth->redirect());
        } else {
            $this->Session->setFlash('Your username/password combination was incorrect');
        }
    }

    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('add', 'register', 'activate', 'forgot_password', 'resset_password');
    }

    public function change_password()
    {
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }
        $id   = $this->Auth->user('id');
        $data = $this->request->data['User'];
        $edit = $this->User->edit($data, $id);
        if ($edit) {
            $this->Session->setFlash('The password has been changed.');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The password cound not be change. Please try again.');
        }
    }

    /**
     * function activate user email.
     */
    public function activate()
    {
        if (empty($this->request->params['pass']['0']) || empty($this->request->params['pass']['1'])) {
            throw new BadRequestException('Bad request');
        }
        $userId     = $this->request->params['pass'][0];
        $token      = $this->request->params['pass'][1];
        $activeUser = $this->User->activate($userId, $token);
        if ($activeUser) {
            $this->Session->setFlash('The user has been activated. Now you can login.');
            $this->redirect(array('action' => 'login'));
        } else {
            $this->Session->setFlash('Activation failed. Click activation link in your email again.');
        }
    }
    /**
     *  TODO resset password page
     */
    public function resset_password()
    {
        if (empty($this->request->params['pass']['0']) || empty($this->request->params['pass']['1'])) {
            throw new BadRequestException('Bad request');
        }
        $userId = $this->request->params['pass'][0];
        $token  = $this->request->params['pass'][1];
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }
        $data           = $this->request->$data['User'];
        $ressetPassword = $this->User->resset_password($userId, $token, $data);
        if ($ressetPassword) {
            $this->Session->setFlash('Your password has been changed. Now you can login');
            $this->redirect(array('action' => 'login'));
        } else {
            $this->Session->setFlash('Resset failed. Click resset link in your email again.');
        }
    }

    /**
     * User registration action
     */
    public function register()
    {
        // Check request METHOD
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }

        // Get user info
        $data = $this->request->data['User'];

        // Create user
        $createdUser = $this->User->createUser($data);

        // Send activation email
        if ($createdUser) {
            $this->_send_activation_email($createdUser['User']);
            $this->Session->setFlash('User has been created. Please follow instruction in sent email.');
        } else {
            $this->Session->setFlash('Unable to create user. Please try again.');
        }
    }

    private function _send_password_resset_email($user)
    {
        $email = new CakeEmail('gmail');
        $email->to($user['email'])
                ->subject('Resset your password form Server Money Lover')
                ->template('forgot_password')
                ->viewVars(array('user' => $user))
                ->send();
    }

    public function forgot_password()
    {
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }
        $data = $this->request->data['User'];

        //check email in database
        $checkEmail = $this->User->checkEmail($data);

        //Send password resset email 
        if ($checkEmail) {
            $this->_send_password_resset_email($checkEmail['User']);
            $this->Session->setFlash('Check you email to return password.');
            $this->redirect(array('action' => 'login'));
        } else {
            $this->Session->setFlash('Email does not exist.');
        }
    }

    /**
     *  Send activate via email
     */
    private function _send_activation_email($user)
    {
        $email = new CakeEmail('gmail');
        $email->to($user['email'])
                ->subject('Please activate your account')
                ->template('activate')
                ->viewVars(array('user' => $user))
                ->send();
    }

    /**
     *  Update profile User
     */
    public function edit()
    {
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }
        $id   = $this->Auth->user('id');
        $data = $this->request->data['User'];
        if ($this->User->edit($data, $id)) {
            $this->Session->setFlash('The user has been saved');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The user cound not be saved. Please try again.');
        }
    }

    /**
     *  Delete User Account
     */
    public function delete()
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        $id     = $this->Auth->User('id');
        $delete = $this->User->delete($id);
        if ($delete) {
            $this->Session->setFlash('User deleted');
            $this->redirect($this->Auth->logout());
        } else {
            $this->Session->setFlash('User was not deleted');
        }
        $this->redirect(array('action' => 'index'));
    }

    /**
     *  function set deffault wallet
     */
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
