
<?php echo $this->Form->create('User'); ?>
<fieldset>
    <legend>Resset Password</legend>
    <?php
    echo $this->Form->input('password');
    echo $this->Form->input('retype_password', array('type' => 'password'));
    ?>
</fieldset>
<?php echo $this->Form->end('Submit'); ?>
