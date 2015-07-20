<?php

App::uses('AppModel', 'Model');

class User extends AppModel
{

    public $name         = 'User';
    public $displayField = 'name';
    public $validate = array(
        'username'         => array(
            'notEmpty' => array(
                'rule'    => 'notBlank',
                'message' => 'Please enter your username'
            ),
            'length'   => array(
                'rule'    => array('between', 5, 15),
                'message' => 'The username must be between 5 and 15 characters.'
            ),
            'unique'   => array(
                'rule'    => 'isUnique',
                'message' => 'That username already been taken.'
            ),
        ),
        'email'            => array(
            'notEmpty'   => array(
                'rule'    => 'notBlank',
                'message' => 'Please enter your email'
            ),
            'validEmail' => array(
                'rule'    => array('email'),
                'message' => 'Please enter a valid email dress'
            ),
        ),
        'password'         => array(
            'notEmpty' => array(
                'rule'    => 'notBlank',
                'message' => 'Please enter your password'
            )
        ),
        'current_password' => array(
            'notEmpty' => array(
                'rule'    => 'notBlank',
                'message' => 'Please enter your password'
            ),
        ),
        'retype_password'  => array(
            'notEmpty'  => array(
                'rule'    => 'notBlank',
                'message' => 'Please retype your password'
            ),
            'matchPass' => array(
                'rule'    => 'passwordsMatch',
                'message' => 'password do not match'
            )
        )
    );

    public function bindHasMany($model, $fKey)
    {
        $this->bindModel(array(
            'hasMany' => array(
                $model => array(
                    'className'  => $model,
                    'foreignKey' => $fKey,
                    'dependent'  => 'true'
                ),
            ),
        ));
    }

    public function bindWallet()
    {
        $this->bindHasMany('Wallet', 'user_id');
    }

    public function bindCategory()
    {
        $this->bindHasMany('Category', 'user_id');
    }

    /**
     *  validate match password 
     * 
     * @param array $data
     * @return boolean
     */
    public function passwordsMatch($data)
    {
        if ($this->data['User']['password'] == $this->data['User']['retype_password']) {
            return true;
        }
        return false;
    }

    /**
     * hash password before save
     * 
     * @param array $options
     * @return boolean
     */
    public function beforeSave($options = array())
    {
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }
        return true;
    }

    /**
     * Create new user
     * 
     * @param array $data User info
     * @return mix
     */
    public function createUser($data)
    {
        $this->create();
        $data['token']     = uniqid();
        $data['activated'] = false;
        return $this->save($data);
    }

    /**
     * save edit urser
     * 
     * @param array $data
     * @param int $id
     * @return type
     */
    public function edit($data, $id)
    {
        $this->id = $id;
        return $this->save($data);
    }

    /**
     * save current wallet to user
     * 
     * @param int $id
     * @param int $walletId
     * @return array
     */
    public function setCurrentWallet($id, $walletId)
    {
        $this->id = $id;
        return ($this->saveField('current_wallet_id', $walletId));
    }

    /**
     *  Generate token for user by email
     * 
     * @param string $email Email
     * @return string|false Token on success, else false
     */
    public function generateTokenForEmail($email)
    {
        $token       = uniqid();
        $db          = $this->getDataSource();
        $quotedToken = $db->value($token, 'string');

        $this->updateAll(array(
            $this->alias . '.token' => $quotedToken,
                ), array(
            $this->alias . '.email' => $email,
        ));

        $count = $this->getAffectedRows();
        return $count > 0 ? $token : false;
    }

    /**
     *  activate user
     * 
     * @param int $userId
     * @param string $token
     * @return mix
     */
    public function activate($userId, $token)
    {
        $data = $this->find('first', array(
            'conditions' => array(
                'User.id'    => $userId,
                'User.token' => $token,
            )
        ));

        if (empty($data)) {
            // User not found or token is not correct
            return false;
        }

        // Else, activate user
        $this->id = $userId;
        return $this->save(array('User' => array(
                        'token'     => null,
                        'activated' => true
        )));
    }

    /**
     *  save new password
     * 
     * @param string $email
     * @param string $token
     * @param array $data
     * @return mix
     */
    public function resetPassword($email, $token, $data)
    {
        $find = $this->find('first', array(
            'conditions' => array(
                'User.email' => $email,
                'User.token' => $token,
            ),
        ));

        if (empty($find)) {
            return;
        }

        $this->id = $find['User']['id'];
        return $this->save(array('User' => array(
                        'token'    => null,
                        'password' => $data['password'],
        )));
    }

    /**
     * get user data by userId
     * 
     * @param int $id
     * @return array
     */
    public function getUserById($id)
    {
        return $this->findById($id);
    }

    /**
     *  delete user by id
     * 
     * @param int $id
     * @return boolean
     */
    public function deleteUserById($id)
    {
        return $this->delete($id);
    }

    /**
     *  Get current wallet by userId
     * 
     * @param int $id
     * @return param
     */
    public function getCurrentWalletIdByUserId($id)
    {
        $data     = $this->getUserById($id);
        if(empty($data)){
            return;
        }
        $walletId = $data['User']['current_wallet_id'];
        return $walletId;
    }

}
