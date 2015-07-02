<div class="wallets view">
    <h2>Users</h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Balance</th>
            <th>Created</th>
            <th class="actions">Actions</th>
        </tr>
        <?php
        $i = 0;
        foreach ($wallets as $wallet):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = 'class="altrow"';
            }
            ?>
            <tr<?php echo $class; ?>>
                <td><?php echo $wallet['Wallet']['name']; ?>&nbsp;</td>
                <td><?php echo $wallet['Wallet']['balance']; ?>&nbsp;</td>
                <td><?php echo $wallet['Wallet']['created']; ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link('Edit', array('action' => 'edit', $wallet['Wallet']['id'])); ?>
                    <?php echo $this->Form->postlink('Delete', array('action' => 'delete', $wallet['Wallet']['id']), null, array('confirm' => 'Are you sure?')); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('New Wallet', array('action' => 'add')); ?></li>
        <li><?php echo $this->Html->link('Back',array('action'=>'index')); ?></li>
    </ul>
</div>