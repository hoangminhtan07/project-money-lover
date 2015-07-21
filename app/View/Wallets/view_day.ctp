
<div class="view day">
    <h2> Date Range</h2>
    <?php foreach ($transactions as $date => $transaction): ?>
        <h4><?php echo $date; ?></h4>
        <table cellpading="0" cellspacing="0">
            <tr>
                <th>Category</th>
                <th>Purpose</th>
                <th>Amount</th>
                <th style="width: 300px">Note</th>
            </tr>
            <?php foreach ($transaction as $tran): ?>
                <tr>
                    <td><?php echo $tran['Category']['name']; ?></td>
                    <?php if ($tran['Category']['purpose'] == 0): ?>
                        <td><?php echo 'Spent'; ?></td>
                    <?php else: ?>
                        <td><?php echo 'Earned'; ?></td>
                    <?php endif; ?>
                    <td><?php echo $tran['Transaction']['amount']; ?></td>
                    <td><?php echo $tran['Transaction']['note']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
    <?php endforeach; ?>
</div>

<div class="actions">
    <h3>Actions</h3>
    <ul>       
        <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index', 'full_base' => true)); ?></li>
    </ul>
</div>

