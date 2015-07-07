<?php if ($logged_in): ?>
    <div class="users index">
        <h2>Users</h2>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th>id</th>
                <th>username</th>
                <th>email</th>
                <th class="actions">Actions</th>
            </tr>
            <tr>
                <td><?php echo $user['User']['id']; ?>&nbsp;</td>
                <td><?php echo $user['User']['username']; ?>&nbsp;</td>
                <td><?php echo $user['User']['email']; ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link('Edit', array('action' => 'edit')); ?>
                    <?php echo $this->Form->postlink('Delete', array('action' => 'delete'), array('confirm' => 'Are you sure?')); ?>

                </td>
            </tr>
        </table>
    </div>
    <div class="actions">
        <h3>Actions</h3>
        <ul>
            <li><?php echo $this->Html->link('Wallet', array('controller' => 'wallets', 'action' => 'index', 'full_base' => true)); ?></li>
            <li><?php echo $this->Html->link('New User', array('controller' => 'users', 'action' => 'register', 'full_base' => true)); ?></li>
        </ul>
    </div>
<?php else: ?>
    <h3>Welcome to Project Money Lover</h3>
<?php endif; ?>