<?php
class User extends AppModel{
    var $name="User";
    function checkLogin($username,$password){
        $sql="Select name,password from users Where username='username' AND password='password' ";
        $this->query($sql);
        if($this->getNumRows()==0){
            return false;
        }
        else {
            return true;
        }
    }
}
?>