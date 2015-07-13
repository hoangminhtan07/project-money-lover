<div class="users form">
    <?php echo $this->Form->create('User'); ?>
    <fieldset>
        <legend>Edit User</legend>
        <?php
        echo $this->Form->input('username', array(
            'default'  => AuthComponent::user('username'),
            'required' => false,
        ));
        echo $this->Form->input('email', array(
            'default'  => AuthComponent::user('email'),
            'required' => false,
        ));
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('Change Password', array('action' => 'change_password')); ?></li>
        <li><?php echo $this->Html->link('Back', array('action' => 'index')); ?></li>
    </ul>
</div>