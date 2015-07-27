<div class="row">
    <div class="col-md-3">
        <h3> Actions</h3>
        <ul>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index')); ?></li>
        </ul>
    </div>
    <div class="col-md-8">
        <h3>Overview</h3>
        <table>
            <tr>
                <th>Balance</th>
                <td><?php echo $currentMoney + $expense - $income . ' (VND)'; ?></td>
                <td></td>
            </tr>
            <tr>
                <th>Income Money</th>
                <td><?php echo $income . '(VND)'; ?></td>
                <td><?php echo round($income / ($income + $expense) * 100, 2) . '%'; ?></td>
            </tr>
            <tr>
                <th>Expense Money</th>
                <td><?php echo $expense . '(VND)'; ?></td>
                <td><?php echo round($expense / ($income + $expense) * 100, 2) . '%'; ?></td>
            </tr>
            <tr>
                <th>Current Money</th>
                <td><?php echo $currentMoney . '(VND)'; ?></td>
                <td></td>
            </tr>

        </table>
    </div>
    <div class="statistic form">
        <h3>Detail</h3>
        <div style="float: right; height: 100px" >
            <?php echo $this->Form->create(); ?>
            <p style="width: 400px"> form month    
                <?php echo $this->Form->month('form'); ?> 
                to month 
                <?php echo $this->Form->month('to'); ?>
            </p>
            <div style="float: right" > <?php echo $this->Form->end('Submit'); ?> </div>
        </div>
        <?php if (isset($sumIncome)): ?>
            <table class="table table-hover">
                <tr>
                <thead>
                <th>Income Money <?php echo $sumIncome . '(VND)'; ?> </th>
                </thead>
                <tbody>
                    <?php if (!empty($cates)): ?> 
                    <td> <?php foreach ($cates as $cate): ?>
                            <?php if ($cate['purpose'] == 1): ?>
                                <p><?php echo $cate['name'] . ':' . $cate['amount'] . '(VND)  ' . round($cate['amount'] / $sumIncome * 100, 2) . '%' ?></p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </td>
                <?php endif; ?>
                </tbody>
                </tr>
                <tr>
                    <th>Expense Money <?php echo $sumExpense . '(VND)'; ?> </th>
                    <?php if (!empty($cates)): ?>
                        <td> <?php foreach ($cates as $cate): ?>
                                <?php if ($cate['purpose'] == 0): ?>
                                    <p><?php echo $cate['name'] . ':' . $cate['amount'] . '(VND)  ' . round($cate['amount'] / $sumExpense * 100, 2) . '%' ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            </table>
        <?php endif; ?>
    </div>
</div>