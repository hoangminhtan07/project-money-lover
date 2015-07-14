<p>Enter your email:</p>
<?php echo $this->Form->create('User'); ?>
<fieldset>
    <legend>Email</legend>
    <?php
    echo $this->Form->input('email', array(
        'required' => false,
        'type' => 'text',
    ));
    ?>
</fieldset>
<?php echo $this->Form->end('Submit'); ?>
