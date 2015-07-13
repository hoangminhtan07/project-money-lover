<?php

App::uses('AppModel', 'Model');

class User extends AppModel
{

    public $name         = 'User';
    public $displayField = 'name';
    public $hasMany      = array(
        'Wallet'   => array(
            'className'  => 'Wallet',
            'foreignKey' => 'user_id',
            'dependent'  => 'true'
        ),
        'Category' => array(
            'className'  => 'Category',
            'foreignKey' => 'user_id',
            'dependent'  => 'true',
        )
    );
    public $validate     = array(
        'username'         => array(
            'length' => array(
                'rule'    => array('between', 5, 15),
                'message' => 'The username must be between 5 and 15 characters.'
            ),
            'unique' => array(
                'rule'    => 'isUnique',
                'message' => 'That username already been taken.'
            ),
        ),
        'email'            => array(
            'validEmail' => array(
                'rule'    => array('email'),
                'message' => 'Please enter a valid email dress'
            )
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

    public function passwordsMatch($data)
    {
        if ($this->data['User']['password'] == $this->data['User']['retype_password']) {
            return true;
        }
        return false;
    }

    public function beforeSave($options = array())
    {
        if (isset($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']); //hash password before save
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
    public function edit($data = null, $id = 0)
    {
        $this->id = $id;
        return ($this->save($data));
    }

    /**
     * save current wallet to user
     * 
     * @param int $id
     * @param int $walletId
     * @return array
     */
    public function setCurrentWallet($id = 0, $walletId = 0)
    {
        $this->id = $id;
        return ($this->saveField('current_wallet_id', $walletId));
    }

    /**
     *  Check Email user forgot password
     * 
     * @param string $data
     */
    public function checkEmail($data)
    {
        $result = $this->find('first', array(
            'conditions' => array(
                'User.email' => $data['email']
            )
        ));

        if (empty($result)) {
            return false;
        } else {
            // create token and save
            $data['token'] = uniqid();
            $this->id      = $result['User']['id'];
            $this->save($data);

            //return user data
            $result1 = $this->find('first', array(
                'conditions' => array(
                    'User.email' => $data['email']
                )
            ));
            return $result1;
        }
    }

    /**
     * 
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
     * 
     * @param int $userId
     * @param string $token
     * @return mix
     */
    public function resset_password($userId, $token, $data)
    {
        //check id and token of user
        $check = $this->find('first', array(
            'conditions' => array(
                'User.id'    => $userId,
                'User.token' => $token,
            )
        ));

        if (empty($check)) {
            return false;
        }

        //save new user password
        $this->id      = $userId;
        $data['token'] = null;
        return $this->save($data);
    }

    public function findUserById($id)
    {
        $data = $this->findById($id);
        return $data;
    }

}
