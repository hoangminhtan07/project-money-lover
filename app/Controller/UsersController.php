<?php
 App::uses('SimplePasswordHasher', 'Controller/Component/Auth');
class UsersController extends AppController {
    public $name='Users';
    public $helpers = array('Html', 'Form');
    
    public function index(){
        $this->User->recursive = 0;
        $this->set('users',$this->User->find('all'));
    }
    public function login(){
        if($this->request->is('post')){
            if($this->Auth->login()){
                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash('Your username/password combination was incorrect');
            }
        }
    }
    
    public function isAuthorized($user) {
        if(in_array($this->action,array('edit','delete','change_password'))){     //find in array action='edit' or 'delete'
            if($user['id'] != $this->request->params['pass'][0]){ // $this->request->params['pass'][0]="id url present"
                return false;
            }
        }
        return true;
    }

    public function logout(){
        $this->redirect($this->Auth->logout());
    }
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add');
    }

    public function view($id=null){
        $this->User->id=$id;
        $this->set('user',$this->User->findById($id));
        if(!$this->User->exists()){
            throw new NotFoundException('Invalid user');
        }
        if(!$id){
            $this->Session->setFlash('Invalid user');
            $this->redirect(array('action'=>'index'));
        }
    }
    
    function change_password($id=null){
        $this->User->id=$id;
        $user=$this->User->findById($this->Auth->user('id'));
        if(!empty($this->data)){
            if(check($this->data['User']['current_password'],$user['User']['password'])){
            if($this->User->save($this->data)){
                $this->Session->setFlash('Password has been change.');
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash('Password could not be change.');
                
            }} else $this->Session->setFlash('aaa');
        } else {
            $this->data=$this->User->findById($this->Auth->user('id'));
        }
    }

    public function add() {
        if($this->request->is('post')){
            if($this->User->save($this->request->data)){
                $this->Session->setFlash('The user has been saved');
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash('The user cound not be saved. Please try again.');
            }
        }
    }
    
    public function edit($id=null){
        $this->User->id=$id;
        $this->set('user',$this->User->findById($id));
        if(!$this->User->exists()){
            throw new NotFoundException('Invalid user');
        }
        if($this->request->is(array('post','put'))){
            
            if($this->User->save($this->request->data)){
                $this->Session->setFlash('The user has been saved');
                $this->redirect(array('action'=>'index'));
            } else {
                $this->Session->setFlash('The user cound not be saved. Please try again.');
            }
        } else {
            $this->request->data=$this->User->read();
        }
    }

    public function delete($id=null){
        if($this->request->is('get')){
            throw new MethodNotAllowedException();
        }
        if(!$id){
            $this->Session->setFlash('Invalid id for user');
            $this->redirect(array('action'=>'index'));
        }
        if($this->User->delete($id)){
            $this->Session->setFlash('User deleted');
            $this->redirect($this->Auth->logout());
            $this->redirect(array('action'=>'index'));
        } else{
            $this->Session->setFlash('User was not deleted');
        }
        $this->redirect(array('action'=>'index'));
    }

}