<?php

class WalletsController extends AppController{
    
    public function index(){
        
    }
    
    public function view(){
        $sql=array('Conditions'=>array('User.id'=>'Wallet.user_id'));
        
        
        $this->set('wallets',$this->Wallet->find('all',$sql));
    }

    public function add(){
        
    }
    
    public function delete(){
        if($this->request->is('get')){
            throw new MethodNotAllowedException();
        }
        if(!$id){
            $this->Session->setFlash('Invalid id for wallet');
            $this->redirect(array('action'=>'index'));
        }
        if($this->Wallet->delete($id)){
            $this->Session->setFlash('Wallet deleted');
            $this->redirect(array('action'=>'index'));
        } else{
            $this->Session->setFlash('Wallet was not deleted');
        }
        $this->redirect(array('action'=>'index'));
    }
    
    public function isAuthorized($user) {
        if(in_array($this->action,array('edit','delete',))){     
            if($user['id'] != $this->request->params['pass'][0]){ 
                return false;
            }
        }
        return true;
    }    
}

