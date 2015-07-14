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

        // Validate inputs
        $this->User->set($this->request->data);
        $this->User->validator()->remove('username', 'unique');
        $valid = $this->User->validates();
        if (!$valid) {
            return;
        }

        if ($this->Auth->login()) {
            $this->redirect($this->Auth->redirect());
        }
        $this->Session->setFlash('Your username/password combination was incorrect');
    }

    public function logout()
    {
        $this->redirect($this->Auth->logout());
    }

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('register', 'activate', 'forgotPassword', 'resetPassword');
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
            $this->Session->setFlash('The password has been changed.');
            $this->redirect(array('action' => 'index'));
        } else {
            $this->Session->setFlash('The password cound not be change. Please try again.');
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
            $this->Session->setFlash('The user has been activated. Now you can login.');
            $this->redirect(array('action' => 'login'));
        } else {
            $this->Session->setFlash('Activation failed. Click activation link in your email again.');
        }
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
            $this->Session->setFlash('Your password has been changed. Now you can login');
            $this->redirect(array('action' => 'login'));
        } else {
            $this->Session->setFlash('Reset failed. Click reset link in your email again.');
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

    /**
     *  Send email to reset password
     * 
     * @param array $user
     * 
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
        $email = $this->request->data['User']['email'];

        //generate token for email
        $token = $this->User->generateTokenForEmail($email);

        //Send password reset email 
        if ($token) {
            $this->_send_password_reset_email($email, $token);
            $this->Session->setFlash('Check you email and follow instruction in sent email.');
            $this->redirect(array('action' => 'login'));
        }
        $this->Session->setFlash('Email does not exist.');
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
        //check request
        if (!$this->request->is(array('post', 'put'))) {
            return;
        }

        $this->User->validator()->getField('username')->setRule('unique', array(
            'rule'    => 'isUnique',
            'message' => 'the username already exists'
        ));
        //get data
        $id   = $this->Auth->user('id');
        $data = $this->request->data['User'];


        //edit user
        if ($this->User->edit($data, $id)) {
            //updating the session user
            $this->Session->write('Auth.User', array_merge(AuthComponent::User(), $this->request->data['User']));

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
        //check request
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        //get userId
        $id = $this->Auth->User('id');

        //delete user
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
     *  function set current wallet
     */
    public function setCurrentWallet($walletId = 0)
    {
        //get userId
        $userId = $this->Auth->user('id');

        //set current wallet
        $set = $this->User->setCurrentWallet($userId, $walletId);
        if ($set) {
            $this->Session->setFlash('Current wallet has been changed.');
            $this->redirect(array('controller' => 'wallets', 'action' => 'view'));
        } else {
            $this->Session->setFlash('Error. Please try again.');
        }
    }

}

?>