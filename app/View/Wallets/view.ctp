<div class="row">
    <div class="col-md-3">
        <h3>Actions</h3>
        <ul>
            <li><?php echo $this->Html->link('New Wallet', array('controller' => 'wallets', 'action' => 'add', 'full_base' => true)); ?></li>
            <li><?php echo $this->Html->link('Transfer money', array('controller' => 'wallets', 'action' => 'transfer', 'full_base' => true)); ?></li>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index', 'full_base' => true)); ?></li>
        </ul>
    </div>
    <div class="col-md-8">
        <h3>Wallets</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Balance</th>
                    <th>Created</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
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
                            <?php echo $this->Html->link('Edit', array('controller' => 'wallets', 'action' => 'edit', $wallet['Wallet']['id'])); ?>
                            <?php echo $this->Form->postlink('Delete', array('controller' => 'wallets', 'action' => 'delete', $wallet['Wallet']['id']), array('confirm' => 'Are you sure?')); ?>
                            <?php echo $this->Html->link('Set Current', array('controller' => 'users', 'action' => 'setCurrentWallet', $wallet['Wallet']['id'])); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>