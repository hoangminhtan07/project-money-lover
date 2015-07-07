<h2>Login</h2>
<?php
echo $this->Form->create();
echo $this->Form->input('username');
echo $this->Form->input('password');
echo $this->Form->end('Login');
?>
<?php echo $this->Html->link('Forgot password',array('action' => 'forgot_password')); ?>