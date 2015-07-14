<div class="users form">
    <?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend>Register</legend>
        <?php
        echo $this->Form->input('current_password', array(
            'type' => 'password',
            'required' => false
            ));
        echo $this->Form->input('password', array(
            'required' => false,
        ));
        echo $this->Form->input('retype_password', array(
            'required' => false,
            'type' => 'password',
            ));
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('Back', array('action' => 'edit')); ?></li>
    </ul>
</d
