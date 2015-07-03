<div class="wallets view">
    <h2>Wallets</h2>
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
                    <?php echo $this->Html->link('Edit', array('controller'=>'wallets','action' => 'edit')); ?>
                    <?php echo $this->Form->postlink('Delete', array('controller'=>'wallets','action' => 'delete'), array('confirm' => 'Are you sure?')); ?>
                    <?php echo $this->Html->link('Set Current',array('controller'=>'wallets','action'=>'set_current')); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('New Wallet', array('controller'=>'wallets','action' => 'add','full_base' => true)); ?></li>
        <li><?php echo $this->Html->link('Back',array('controller'=>'wallets','action'=>'index','full_base' => true)); ?></li>
    </ul>
</div>