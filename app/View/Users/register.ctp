<div class="users form">
    <?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend>Register</legend>
        <?php
        echo $this->Form->input('username', array(
            'required' => false,
        ));
        echo $this->Form->input('email', array(
            'required' => false,
        ));
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
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('Back', array('action' => 'index')); ?></li>
    </ul>
</div>