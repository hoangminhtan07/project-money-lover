
<?php echo $this->Form->create('User'); ?>
<fieldset>
    <legend>New Password</legend>
    <?php
    echo $this->Form->input('password', array(
        'required' => false,
    ));
    echo $this->Form->input('retype_password', array(
        'type' => 'password',
        'required' => false,
        ));
    ?>
</fieldset>
<?php echo $this->Form->end('Submit'); ?>