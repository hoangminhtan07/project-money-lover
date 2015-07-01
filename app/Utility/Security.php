<?php
  /**
  3:  * Core Security
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
 14:  * @package       Cake.Utility
 15:  * @since         CakePHP(tm) v .0.10.0.1233
 16:  * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 17:  */
  
  App::uses('String', 'Utility');
 
/**
 22:  * Security Library contains utility methods related to security
 23:  *
 24:  * @package       Cake.Utility
 25:  */
 class Security {
 
 /**
 29:  * Default hash method
 30:  *
 31:  * @var string
 32:  */
     public static $hashType = null;
 
  /**
 36:  * Default cost
 37:  *
 38:  * @var string
 39:  */
      public static $hashCost = '10';
 
  /**
 43:  * Get allowed minutes of inactivity based on security level.
 44:  *
 45:  * @deprecated 3.0.0 Exists for backwards compatibility only, not used by the core
 46:  * @return int Allowed inactivity in minutes
 47:  */
      public static function inactiveMins() {
          switch (Configure::read('Security.level')) {
             case 'high':
                 return 10;
             case 'medium':
                  return 100;
             case 'low':
              default:
                  return 300;
         }
     }

  /**
 61:  * Generate authorization hash.
 62:  *
 63:  * @return string Hash
 64:  */
      public static function generateAuthKey() {
          return Security::hash(String::uuid());
      }
  
  /**
 70:  * Validate authorization hash.
 71:  *
 72:  * @param string $authKey Authorization hash
 73:  * @return bool Success
 74:  */
      public static function validateAuthKey($authKey) {
          return true;
      }
  
  /**
 80:  * Create a hash from string using given method or fallback on next available method.
 81:  *
 82:  * #### Using Blowfish
 83:  *
 84:  * - Creating Hashes: *Do not supply a salt*. Cake handles salt creation for
 85:  * you ensuring that each hashed password will have a *unique* salt.
 86:  * - Comparing Hashes: Simply pass the originally hashed password as the salt.
 87:  * The salt is prepended to the hash and php handles the parsing automagically.
 88:  * For convenience the `BlowfishPasswordHasher` class is available for use with
 89:  * the AuthComponent.
 90:  * - Do NOT use a constant salt for blowfish!
 91:  *
 92:  * Creating a blowfish/bcrypt hash:
 93:  *
 94:  * ```
 95:  *  $hash = Security::hash($password, 'blowfish');
 96:  * ```
 97:  *
 98:  * @param string $string String to hash
 99:  * @param string $type Method to use (sha1/sha256/md5/blowfish)
100:  * @param mixed $salt If true, automatically prepends the application's salt
101:  *     value to $string (Security.salt). If you are using blowfish the salt
102:  *     must be false or a previously generated salt.
103:  * @return string Hash
104:  * @link http://book.cakephp.org/2.0/en/core-utility-libraries/security.html#Security::hash
105:  */
     public static function hash($string, $type = null, $salt = false) {
         if (empty($type)) {
             $type = self::$hashType;
         }
         $type = strtolower($type);
 
         if ($type === 'blowfish') {
             return self::_crypt($string, $salt);
        }
        if ($salt) {
            if (!is_string($salt)) {
                 $salt = Configure::read('Security.salt');
            }
             $string = $salt . $string;
         }
 
         if (!$type || $type === 'sha1') {
             if (function_exists('sha1')) {
                 return sha1($string);
            }
             $type = 'sha256';
         }
 
         if ($type === 'sha256' && function_exists('mhash')) {
             return bin2hex(mhash(MHASH_SHA256, $string));
         }
 
         if (function_exists('hash')) {
             return hash($type, $string);
         }
         return md5($string);
     }
 
 /**
140:  * Sets the default hash method for the Security object. This affects all objects using
141:  * Security::hash().
142:  *
143:  * @param string $hash Method to use (sha1/sha256/md5/blowfish)
144:  * @return void
145:  * @see Security::hash()
146:  */
     public static function setHash($hash) {
         self::$hashType = $hash;
    }
 
 /**
152:  * Sets the cost for they blowfish hash method.
153:  *
154:  * @param int $cost Valid values are 4-31
155:  * @return void
156:  */
     public static function setCost($cost) {
         if ($cost < 4 || $cost > 31) {
             trigger_error(__d(
                 'cake_dev',
                 'Invalid value, cost must be between %s and %s',
                 array(4, 31)
             ), E_USER_WARNING);
             return null;
         }
         self::$hashCost = $cost;
     }

/**
170:  * Runs $text through a XOR cipher.
171:  *
172:  * *Note* This is not a cryptographically strong method and should not be used
173:  * for sensitive data. Additionally this method does *not* work in environments
174:  * where suhosin is enabled.
175:  *
176:  * Instead you should use Security::rijndael() when you need strong
177:  * encryption.
178:  *
179:  * @param string $text Encrypted string to decrypt, normal string to encrypt
180:  * @param string $key Key to use
181:  * @return string Encrypted/Decrypted string
182:  * @deprecated 3.0.0 Will be removed in 3.0.
183:  */
     public static function cipher($text, $key) {
         if (empty($key)) {
             trigger_error(__d('cake_dev', 'You cannot use an empty key for %s', 'Security::cipher()'), E_USER_WARNING);
             return '';
         }
 
         srand(Configure::read('Security.cipherSeed'));
         $out = '';
         $keyLength = strlen($key);
         for ($i = 0, $textLength = strlen($text); $i < $textLength; $i++) {
             $j = ord(substr($key, $i % $keyLength, 1));
             while ($j--) {
                 rand(0, 255);
             }
             $mask = rand(0, 255);
             $out .= chr(ord(substr($text, $i, 1)) ^ $mask);
         }
         srand();
         return $out;
     }
 
 /**
206:  * Encrypts/Decrypts a text using the given key using rijndael method.
207:  *
208:  * Prior to 2.3.1, a fixed initialization vector was used. This was not
209:  * secure. This method now uses a random iv, and will silently upgrade values when
210:  * they are re-encrypted.
211:  *
212:  * @param string $text Encrypted string to decrypt, normal string to encrypt
213:  * @param string $key Key to use as the encryption key for encrypted data.
214:  * @param string $operation Operation to perform, encrypt or decrypt
215:  * @return string Encrypted/Decrypted string
216:  */
     public static function rijndael($text, $key, $operation) {
         if (empty($key)) {
             trigger_error(__d('cake_dev', 'You cannot use an empty key for %s', 'Security::rijndael()'), E_USER_WARNING);
             return '';
        }
         if (empty($operation) || !in_array($operation, array('encrypt', 'decrypt'))) {
             trigger_error(__d('cake_dev', 'You must specify the operation for Security::rijndael(), either encrypt or decrypt'), E_USER_WARNING);
            return '';
         }
         if (strlen($key) < 32) {
             trigger_error(__d('cake_dev', 'You must use a key larger than 32 bytes for Security::rijndael()'), E_USER_WARNING);
             return '';
         }
         $algorithm = MCRYPT_RIJNDAEL_256;
         $mode = MCRYPT_MODE_CBC;
         $ivSize = mcrypt_get_iv_size($algorithm, $mode);
 
         $cryptKey = substr($key, 0, 32);

         if ($operation === 'encrypt') {
             $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
             return $iv . '$$' . mcrypt_encrypt($algorithm, $cryptKey, $text, $mode, $iv);
         }
         // Backwards compatible decrypt with fixed iv
         if (substr($text, $ivSize, 2) !== '$$') {
             $iv = substr($key, strlen($key) - 32, 32);
             return rtrim(mcrypt_decrypt($algorithm, $cryptKey, $text, $mode, $iv), "\0");
         }
         $iv = substr($text, 0, $ivSize);
         $text = substr($text, $ivSize + 2);
         return rtrim(mcrypt_decrypt($algorithm, $cryptKey, $text, $mode, $iv), "\0");
     }
 
 /**
251:  * Generates a pseudo random salt suitable for use with php's crypt() function.
252:  * The salt length should not exceed 27. The salt will be composed of
253:  * [./0-9A-Za-z]{$length}.
254:  *
255:  * @param int $length The length of the returned salt
256:  * @return string The generated salt
257:  */
     protected static function _salt($length = 22) {
         $salt = str_replace(
             array('+', '='),
             '.',
             base64_encode(sha1(uniqid(Configure::read('Security.salt'), true), true))
         );
         return substr($salt, 0, $length);
     }
 
 /**
268:  * One way encryption using php's crypt() function. To use blowfish hashing see ``Security::hash()``
269:  *
270:  * @param string $password The string to be encrypted.
271:  * @param mixed $salt false to generate a new salt or an existing salt.
272:  * @return string The hashed string or an empty string on error.
273:  */
     protected static function _crypt($password, $salt = false) {
         if ($salt === false) {
             $salt = self::_salt(22);
             $salt = vsprintf('$2a$%02d$%s', array(self::$hashCost, $salt));
         }
 
         $invalidCipher = (
             strpos($salt, '$2y$') !== 0 &&
             strpos($salt, '$2x$') !== 0 &&
             strpos($salt, '$2a$') !== 0
         );
        if ($salt === true || $invalidCipher || strlen($salt) < 29) {
             trigger_error(__d(
                 'cake_dev',
                 'Invalid salt: %s for %s Please visit http://www.php.net/crypt and read the appropriate section for building %s salts.',
                 array($salt, 'blowfish', 'blowfish')
            ), E_USER_WARNING);
             return '';
         }
         return crypt($password, $salt);
     }
 
 /**
297:  * Encrypt a value using AES-256.
298:  *
299:  * *Caveat* You cannot properly encrypt/decrypt data with trailing null bytes.
300:  * Any trailing null bytes will be removed on decryption due to how PHP pads messages
301:  * with nulls prior to encryption.
302:  *
303:  * @param string $plain The value to encrypt.
304:  * @param string $key The 256 bit/32 byte key to use as a cipher key.
305:  * @param string $hmacSalt The salt to use for the HMAC process. Leave null to use Security.salt.
306:  * @return string Encrypted data.
307:  * @throws CakeException On invalid data or key.
308:  */
     public static function encrypt($plain, $key, $hmacSalt = null) {
         self::_checkKey($key, 'encrypt()');
 
         if ($hmacSalt === null) {
             $hmacSalt = Configure::read('Security.salt');
         }
 
         // Generate the encryption and hmac key.
         $key = substr(hash('sha256', $key . $hmacSalt), 0, 32);
 
         $algorithm = MCRYPT_RIJNDAEL_128;
         $mode = MCRYPT_MODE_CBC;
 
         $ivSize = mcrypt_get_iv_size($algorithm, $mode);
         $iv = mcrypt_create_iv($ivSize, MCRYPT_DEV_URANDOM);
         $ciphertext = $iv . mcrypt_encrypt($algorithm, $key, $plain, $mode, $iv);
         $hmac = hash_hmac('sha256', $ciphertext, $key);
         return $hmac . $ciphertext;
     }

 /**
330:  * Check the encryption key for proper length.
331:  *
332:  * @param string $key Key to check.
333:  * @param string $method The method the key is being checked for.
334:  * @return void
335:  * @throws CakeException When key length is not 256 bit/32 bytes
336:  */
     protected static function _checkKey($key, $method) {
         if (strlen($key) < 32) {
             throw new CakeException(__d('cake_dev', 'Invalid key for %s, key must be at least 256 bits (32 bytes) long.', $method));
         }
     }
 
 /**
344:  * Decrypt a value using AES-256.
345:  *
346:  * @param string $cipher The ciphertext to decrypt.
347:  * @param string $key The 256 bit/32 byte key to use as a cipher key.
348:  * @param string $hmacSalt The salt to use for the HMAC process. Leave null to use Security.salt.
349:  * @return string Decrypted data. Any trailing null bytes will be removed.
350:  * @throws CakeException On invalid data or key.
351:  */
     public static function decrypt($cipher, $key, $hmacSalt = null) {
         self::_checkKey($key, 'decrypt()');
         if (empty($cipher)) {
             throw new CakeException(__d('cake_dev', 'The data to decrypt cannot be empty.'));
         }
         if ($hmacSalt === null) {
             $hmacSalt = Configure::read('Security.salt');
         }
 
         // Generate the encryption and hmac key.
         $key = substr(hash('sha256', $key . $hmacSalt), 0, 32);
 
         // Split out hmac for comparison
         $macSize = 64;
         $hmac = substr($cipher, 0, $macSize);
         $cipher = substr($cipher, $macSize);
 
         $compareHmac = hash_hmac('sha256', $cipher, $key);
         if ($hmac !== $compareHmac) {
             return false;
         }

         $algorithm = MCRYPT_RIJNDAEL_128;
         $mode = MCRYPT_MODE_CBC;
         $ivSize = mcrypt_get_iv_size($algorithm, $mode);
 
         $iv = substr($cipher, 0, $ivSize);
         $cipher = substr($cipher, $ivSize);
         $plain = mcrypt_decrypt($algorithm, $key, $cipher, $mode, $iv);
         return rtrim($plain, "\0");
     }
 
 }