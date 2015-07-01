<h2>Change Password</h2>
<?php
    echo $this->Form->create('User');
    echo $this->Form->input('current_password',array('type'=>'password'));
    echo $this->Form->input('password');
    echo $this->Form->input('retype_password',array('type'=>'password'));
    echo $this->Form->end('Submit');
?>
    