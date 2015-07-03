<?php

//App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class UsersController extends AppController {

    public $name = 'Users';
    public $helpers = array('Html', 'Form');

    public function index() {
        $id = $this->Auth->user('id');
        $this->set('user', $this->User->findById($id));
    }

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash('Your username/password combination was incorrect');
            }
        }
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add');
    }

    public function view($id = null) {
        if (!$this->User->exists()) {
            throw new NotFoundException('Invalid user');
        }
        if (!$id) {
            $this->Session->setFlash('Invalid user');
            $this->redirect(array('action' => 'index'));
        }
        $this->User->id = $id;
        $this->set('user', $this->User->findById($id));
    }

    function change_password() {
        if ($this->request->is(array('post', 'put'))) {
            $id = $this->Auth->user('id');
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

    public function add() {
        if ($this->request->is('post')) {
            $data = $this->request->data['User'];
            if ($this->User->add($data)) {
                $this->Session->setFlash('The user has been saved');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The user cound not be saved. Please try again.');
            }
        }
    }

    public function edit() {
        if ($this->request->is(array('post', 'put'))) {
            $id = $this->Auth->user('id');
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

    public function delete() {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        if (!$id) {
            $this->Session->setFlash('Invalid id for user');
            $this->redirect(array('action' => 'index'));
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

}
