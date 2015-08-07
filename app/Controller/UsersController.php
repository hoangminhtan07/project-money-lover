<?php

class UsersController extends AppController
{

    public $uses    = array('User', 'Wallet', 'Category', 'Transaction');
    public $helpers = array('Html', 'Form');

    public function index()
    {
        
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

        // Validate inputs
        $this->User->set($this->request->data);
        $this->User->validator()->remove('username', 'unique');
        $valid = $this->User->validates();
        if (!$valid) {
            return;
        }

        if ($this->Auth->login()) {
            $rememberMe = $this->request->data['User']['rememberMe'];

            if ($rememberMe) {
                $cookieTime = "12 months";

                //remove remember me checkbox
                unset($rememberMe);

                //hash the user's password
                $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['password']);

                //write the cookie
                $this->Cookie->write('rememberMe', $this->request->data['User'], true, $cookieTime);
            }
            $this->redirect($this->Auth->redirectUrl());
        }
        $this->Session->setFlash(__('Your username/password combination was incorrect.'), 'alert_box', array('class' => 'alert-danger'));
    }

    public function logout()
    {
        $this->Session->setFlash(__('You have been logged out'), 'alert_box', array('class' => 'alert-success'));
        $this->Cookie->delete('rememberMe');
        $this->redirect($this->Auth->logout());
    }

    public function beforeFilter()
    {
        $this->Auth->allow('index', 'login', 'register', 'activate', 'forgotPassword', 'resetPassword');
    }

    /**
     *  Change password after user login
     */
    public function changePassword()
    {
        //check request
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }

        //get data
        $id   = $this->Auth->user('id');
        $data = $this->request->data['User'];

        //change password
        $edit = $this->User->edit($data, $id);
        if ($edit) {
            $this->Session->setFlash(__('The password has been changed.'), 'alert_box', array('class' => 'alert-success'));
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash(__('The password could not be changed. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
    }

    /**
     *  Activate user email
     * 
     * @param int $userId
     * @param string $token
     * 
     */
    public function activate($userId, $token)
    {
        //check request
        if (empty($userId) || empty($token)) {
            throw new BadRequestException('Bad request');
        }

        $activeUser = $this->User->activate($userId, $token);
        if ($activeUser) {
            $this->Session->setFlash(__('The user has been activated. Now you can login.'), 'alert_box', array('class' => 'alert-success'));
        } else {
            $this->Session->setFlash(__('Activation failed. Click activation link in your email again.'), 'alert_box', array('class' => 'alert-danger'));
        }
        $this->redirect(array('action' => 'login'));
    }

    /**
     *   reset password 
     * 
     * @param string $email
     * @param string $token
     */
    public function resetPassword($email, $token)
    {
        //Check empty params
        if (empty($email) || empty($token)) {
            throw new BadRequestException('Bad request');
        }

        //check request
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }

        //load validation
        $this->User->set($this->request->data);
        $valid = $this->User->validates();
        if (!$valid) {
            return;
        }
        //get data request
        $data = $this->request->data['User'];

        //check and save data
        $resetPassword = $this->User->resetPassword($email, $token, $data);
        if ($resetPassword) {
            $this->Session->setFlash(__('Your password has been changed. Now you can login'), 'alert_box', array('class' => 'alert-success'));
        } else {
            $this->Session->setFlash(__('Reset failed. Click reset link in your email again.'), 'alert_box', array('class' => 'alert-danger'));
        }
        $this->redirect(array('action' => 'login'));
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

        // Validate inputs
        $this->User->set($this->request->data);
        $valid = $this->User->validates();
        if (!$valid) {
            return;
        }

        // Get user info
        $data = $this->request->data['User'];

        // Create user
        $createdUser = $this->User->createUser($data);

        // Send activation email
        if ($createdUser) {
            $this->_send_activation_email($createdUser['User']);
            $this->Session->setFlash(__('User has been created. Please follow instruction in sent email.'), 'alert_box', array('class' => 'alert-success'));
        } else {
            $this->Session->setFlash(__('Unable to create user. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
            return;
        }
        $this->redirect(array('action' => 'index'));
    }

    /**
     *  Send email to reset password
     * 
     * @param string $email
     * @param string $token
     */
    private function _send_password_reset_email($email, $token)
    {
        $emailObj = new CakeEmail('gmail');
        $emailObj->to($email)
                ->subject('Reset your password form Server Money Lover')
                ->template('forgotPassword')
                ->viewVars(array(
                    'token' => $token,
                    'email' => $email,
                ))
                ->send();
    }

    /**
     *  Forgot password activate
     */
    public function forgotPassword()
    {
        //check request
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }

        // Validate inputs
        $this->User->set($this->request->data);
        $valid = $this->User->validates();
        if (!$valid) {
            return;
        }

        //get data
        //$email = $this->request->data['User']['email'];
        $email = $this->request->data['email'];
        //generate token for email
        $token = $this->User->generateTokenForEmail($email);

        //Send password reset email 
        if ($token) {
            $this->_send_password_reset_email($email, $token);
            $this->Session->setFlash(__('Check you email and follow instruction in sent email.'), 'alert_box', array('class' => 'alert-success'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('Email does not exist.'), 'alert_box', array('class' => 'alert-danger'));
    }

    /**
     *  Send activate via email
     * @param array $user
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
        //check request
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }

        //get data
        $id   = $this->Auth->user('id');
        $data = $this->request->data['User'];


        //edit user
        if ($this->User->edit($data, $id)) {
            //updating the session user
            $this->Session->write('Auth.User', array_merge(AuthComponent::User(), $this->request->data['User']));

            $this->Session->setFlash(__('The user has been saved'), 'alert_box', array('class' => 'alert-success'));
            $this->redirect(array('controller' => 'wallets', 'action' => 'index'));
        } else {
            $this->Session->setFlash(__('The user cound not be saved. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
    }

    /**
     *  Delete User Account
     */
    public function delete()
    {
        //check request
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        //get userId
        $id = $this->Auth->User('id');

        $delete = $this->User->deleteUserById($id);
        if ($delete) {
            $this->Session->setFlash(__('User deleted'), 'alert_box', array('class' => 'alert-success'));
            $this->redirect($this->Auth->logout());
        } else {
            $this->Session->setFlash(__('User was not deleted. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
        $this->redirect(array('action' => 'index'));
    }

    /**
     *  function set current wallet
     * @param int $walletId
     */
    public function setCurrentWallet($walletId)
    {
        //get userId
        $userId = $this->Auth->user('id');

        //set current wallet
        $set = $this->User->setCurrentWallet($userId, $walletId);
        if ($set) {
            $this->Session->setFlash(__('Current wallet has been changed.'), 'alert_box', array('class' => 'alert-success'));
            $this->redirect(array('controller' => 'wallets', 'action' => 'view'));
        } else {
            $this->Session->setFlash(__('Error. Please try again.'), 'alert_box', array('class' => 'alert-danger'));
        }
    }

}
