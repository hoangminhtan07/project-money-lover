
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 
 <?php
  /**
   *
 4:  *
 5:  * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 6:  * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 7:  *
 8:  * Licensed under The MIT License
 9:  * For full copyright and license information, please see the LICENSE.txt
10:  * Redistributions of files must retain the above copyright notice.
11:  *
12:  * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
13:  * @link          http://cakephp.org CakePHP(tm) Project
14:  * @since         CakePHP(tm) v 2.4.0
15:  * @license       http://www.opensource.org/licenses/mit-license.php MIT License
16:  */
 
 App::uses('AbstractPasswordHasher', 'Controller/Component/Auth');
 App::uses('Security', 'Utility'); 
 /**
  * Simple password hashing class.
23:  *
24:  * @package       Cake.Controller.Component.Auth
25:  */
class SimplePasswordHasher extends AbstractPasswordHasher { 
 /**
  * Config for this object.
 *
  * @var array
  */
     protected $_config = array('hashType' => null);
 
 /**
  * Generates password hash.
  *
38:  * @param string $password Plain text password to hash.
39:  * @return string Password hash
40:  * @link http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html#hashing-passwords
41:  */
     public function hash($password) {
         return Security::hash($password, $this->_config['hashType'], true);
    } 
 /**
47:  * Check hash. Generate hash for user provided password and check against existing hash.
48:  *
49:  * @param string $password Plain text password to hash.
50:  * @param string Existing hashed password.
51:  * @return boolean True if hashes match else false.
52:  */
     public function check($password, $hashedPassword) {
         return $hashedPassword === $this->hash($password);
    }

 }