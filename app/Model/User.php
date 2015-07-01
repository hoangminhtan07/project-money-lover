<?php
 App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

class User extends AppModel {
    public $name = 'User';
    public $displayField = 'name';
    public $validate = array(
        'username' => array(
            'The username must be between 5 and 15 characters.' => array(
                'rule' => array('between', 5, 15),
                'message' => 'The username must be between 5 and 15 characters.'
            ),
            'That username has already been taken.'=>array(
                'rule'=>'isUnique',
                'message'=>'That username already been taken.'
            ),
        ),
        'email' => array(
          'Valid email' => array(
            'rule' => array('email'),
            'message' => 'Please enter a valid email dress'
            )
        ),
        'password' => array(
            'Not empty' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter your password'
            )
        ),
        'password_confirmation' => array(
            'Not empty' => array(
            'rule' => 'notBlank',
            'message' => 'Please confirm your password'
            ),
            'Match passwords' => array(
                'rule' => 'matchPasswords',
                'message' => 'Your passwords do not match'
            )
        ),
        'current_password'=>array(
            'Not empty' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter your password'
            ),
            'Valid'=>array(
                'rule'=>'checkCurrentPassword',
                'message'=>'Current password wrong'
                )
        ),
        'new_password'=>array(
            'Not empty' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter your new password'
            )
        ),
        'retype_password'=>array(
            'Not empty' => array(
                'rule' => 'notBlank',
                'message' => 'Please retype your password'
            ),
            'Match pass'=>array(
                'rule'=>'passwordsMatch',
                'message'=>'password do not match'
                )
        )
    );
 
    public function checkCurrentPassword($data){
       // $this->data['User']['current_password']= AuthComponent::password($this->data['User']['current_password']);
        //$user=$this->User->findById($this->Auth->user('id'));
       // if($this->data['User']['current_password']==$user['User']['password']){
            return true;
       // } else {
       //     return false;
       // }
    }

    public function passwordsMatch($data){
        if($this->data['User']['password']==$this->data['User']['retype_password']){
            return true;
        }
        return false;
    }

    public function matchPasswords($data){
        if($this->data['User']['password']==$this->data['User']['password_confirmation']){
            return true;
        }
        return false;
    }
    
    public function beforeSave($options=array()) {
        if(isset($this->data['User']['password'])){
            $this->data['User']['password']=  AuthComponent::password($this->data['User']['password']); //hash password before save
        }
        return true;
    }

}