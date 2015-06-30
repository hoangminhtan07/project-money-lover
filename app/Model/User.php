<?php

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
            ),
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
        return true;
    }

    public function passwordsMatch($data){
        if($this->data['User']['new_password']==$this->data['User']['retype_password']){
            return true;
        }
        $this->invalidate('retype_password','Your passwords do not match');
        return false;
    }

    public function matchPasswords($data){
        if($data['password']==$this->data['User']['password_confirmation']){
            return true;
        }
        $this->invalidate('password_confirmaition','Your passwords do not match');
        return false;
    }
    
    public function beforeSave($options=array()) {
        if(isset($this->data['User']['password'])){
            $this->data['User']['password']=  AuthComponent::password($this->data['User']['password']);
        }
        return true;
    }

}