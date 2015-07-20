
<div class="users index">
    <h2> <?php echo $wallet['Wallet']['name'] . ':' . ' ' . 'Money :' . $wallet['Wallet']['balance'] . '(VND)'; ?></h2>
    <?php echo $this->Html->link('Sort By Date Range', array('action' => 'viewDay', $wallet['Wallet']['id'])); ?>
    <?php echo $this->Html->link('Sort By Category', array('action' => 'viewCategory', $wallet['Wallet']['id'])); ?>
    <table cellpading="0" cellspacing="0">
        <tr>
            <th>Category</th>
            <th>Purpose</th>
            <th>Amount</th>
            <th>Note</th>
            <th>Created</th>
            <th>Modified</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($transactions as $transaction): ?>
            <tr>
                <td> <?php echo $transaction['Category']['name']; ?></td>
                <?php if ($transaction['Category']['purpose'] == 0): ?>
                    <td><?php echo 'Spent'; ?></td>
                <?php else: ?>
                    <td><?php echo 'Earned'; ?></td>
                <?php endif; ?>
                <td><?php echo $transaction['Transaction']['amount']; ?></td>
                <td><?php echo $transaction['Transaction']['note']; ?></td>
                <td><?php echo $transaction['Transaction']['created']; ?></td>
                <td><?php echo $transaction['Transaction']['modified']; ?></td>
                <td class="actions">
                    <?php echo $this->Html->link('Edit', array('controller' => 'transactions', 'action' => 'edit', $transaction['Transaction']['id'])); ?>
                    <?php echo $this->Form->postlink('Delete', array('controller' => 'transactions', 'action' => 'delete', $transaction['Transaction']['id']), array('confirm' => 'Are you sure?')); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php echo $this->Html->link('Add Transaction', array('controller' => 'transactions', 'action' => 'add')); ?>
</div>

<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('Edit User', array('controller' => 'users', 'action' => 'edit')); ?></li>
        <li><?php echo $this->Html->link('View All Wallets', array('controller' => 'wallets', 'action' => 'view')); ?></li>
        <li><?php echo $this->Html->link('Categories', array('controller' => 'categories', 'action' => 'index', 'full_base' => true)); ?></li>
        <?php if (!empty($transactions)): ?>
            <li><?php echo $this->Html->link('Statistics', array('controller' => 'transactions', 'action' => 'statistic', 'full_base' => true)); ?></li>
        <?php endif; ?>
        <li><?php echo $this->Html->link('Back', array('controller' => 'users', 'action' => 'index', 'full_base' => true)); ?></li>

    </ul>
</div>

