
<div class="users index">
    <h2> <?php echo $wallet['Wallet']['name'] . ':' . ' ' . 'Money :' . $wallet['Wallet']['balance'] . '(VND)'; ?></h2>
    <table cellpading="0" cellspacing="0">
        <tr>
            <th>Category</th>
            <th>Purpose</th>
            <th>Amount</th>
            <th>Note</th>
            <th>Created</th>
            <th>Modified</th>
        </tr>
        <!--<?php foreach ($transactions as $transaction): ?>
            <tr>
                <td> <?php echo $transaction['Transaction']['']; ?></td>
            </tr>
        <?php endforeach; ?>-->
    </table>
    <?php echo $this->Html->link('Add Transaction', array('controller' => 'transactions', 'action' => 'add')); ?>
</div>



<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('View All Wallets', array('controller' => 'wallets', 'action' => 'view')); ?></li>
        <li><?php echo $this->Html->link('Categories', array('controller' => 'categories', 'action' => 'index', 'full_base' => true)); ?></li>
        <li><?php echo $this->Html->link('Statistics', array('controller' => '', 'action' => '', 'full_base' => true)); ?></li>
        <li><?php echo $this->Html->link('Back', array('controller' => 'users', 'action' => 'index', 'full_base' => true)); ?></li>

    </ul>
</div>

