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
                <?php echo $this->Form->postlink('Delete', array('action' => 'delete'), null, array('confirm' => 'Are you sure?')); ?>

            </td>
        </tr>
    </table>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('Wallet', array('action' => 'Wallets/index')); ?></li>
        <li><?php echo $this->Html->link('New User', array('action' => 'add')); ?></li>
    </ul>
</div>