<?php 
class UsersController extends AppController{
        //var $layout=false;
        var $name="Users";
        var $helpers=array("Html");
        var $component=array("Session");
        var $_sessionUsername="Username";
	function index(){
	$this->set('users',$this->User->find('all'));
	}
        function view(){
            if($this->Session->read($this->_sessionUsername)){
                $this->redirect("login");
            }
            else{
                $this->render("Users/index");
            }
        }
        function login(){
            $error="";
            if($this->Session->read($this->_SessionUsername)){
                $this->redirect("view");
            }
            if(isset($_POST['ok'])){
                $username=$POST['username'];
                $password=$_POST['password'];
                if($this->User->checkLogin($username,$password)){
                    $this->Session->write($this->_sessionUsername,$username);
                    $this->redirect("view");
                }
                else{
                    $error="Username or Password wrong";
                }
            }
            $this->set("error",$error);
            $this->render("Users/login");
        }
        function logout(){
            $this->Session->delete($this->_sessionUsername);
            $this->redirect("login");
        }

}
?>
