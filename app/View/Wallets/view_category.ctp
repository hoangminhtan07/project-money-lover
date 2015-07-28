<div class="row">
    <div class="col-md-2">
        <h3>Actions</h3>
        <ul>       
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index', 'full_base' => true)); ?></li>
        </ul>
    </div>
    <div class="col-md-8">
        <h2 style="text-align: center">List Category</h2>
        <?php foreach ($transactions as $name => $transaction): ?>
            <h4><?php echo $name; ?></h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Purpose</th>
                        <th >Date</th>
                        <th>Amount</th>
                        <th style="width: 300px">Note</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaction as $tran): ?>
                        <tr>
                            <?php if ($tran['Category']['purpose'] == 0): ?>
                                <td><?php echo 'Spent'; ?></td>
                            <?php else: ?>
                                <td><?php echo 'Earned'; ?></td>
                            <?php endif; ?>
                            <td><?php echo $tran['Transaction']['created']; ?></td>
                            <td><?php echo $tran['Transaction']['amount']; ?></td>
                            <td><?php echo $tran['Transaction']['note']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br>
        <?php endforeach; ?>
    </div>
</div>


