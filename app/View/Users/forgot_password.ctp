<p>Enter your email:</p>
<?php echo $this->Form->create('User'); ?>
<fieldset>
    <legend>Email</legend>
    <?php
    echo $this->Form->input('email')
    ?>
</fieldset>
<?php echo $this->Form->end('Submit'); ?>
