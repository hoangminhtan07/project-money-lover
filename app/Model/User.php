<?php

class User extends AppModel {

    public $name = 'User';
    public $displayField = 'name';
    public $hasMany = array(
        'Wallet' => array(
            'className' => 'Wallet',
            'foreignKey' => 'user_id',
            'dependent' => 'true'
        ),
        'Category' => array(
            'className' => 'Category',
            'foreignKey' => 'user_id',
            'dependent' => 'true',
        )
    );
    public $validate = array(
        'username' => array(
            'length' => array(
                'rule' => array('between', 5, 15),
                'message' => 'The username must be between 5 and 15 characters.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'That username already been taken.'
            ),
        ),
        'email' => array(
            'validEmail' => array(
                'rule' => array('email'),
                'message' => 'Please enter a valid email dress'
            )
        ),
        'password' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter your password'
            )
        ),
        'current_password' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter your password'
            ),
        /* 'valid' => array(
          'rule' => 'checkCurrentPassword',
          'message' => 'Current password wrong'
          ) */
        ),
        'retype_password' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Please retype your password'
            ),
            'matchPass' => array(
                'rule' => 'passwordsMatch',
                'message' => 'password do not match'
            )
        )
    );

    /* public function checkCurrentPassword($data) {
      if (1) {
      return true;
      } else {
      return false;
      }
      } */

    public function passwordsMatch($data) {
        if ($this->data['User']['password'] == $this->data['User']['retype_password']) {
            return true;
        }
        return false;
    }

    public function beforeSave($options = array()) {
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']); //hash password before save
        }
        return true;
    }

    public function add($data = null) {
        $this->create();
        if ($this->save($data)) {
            return ($this->save($data));
        } else {
            return ($this->save($data));
        }
    }

    public function edit($data = null, $id = 0) {
        $this->id = $id;
        if ($this->save($data)) {
            return ($this->save($data));
        } else {
            return ($this->save($data));
        }
    }

}
