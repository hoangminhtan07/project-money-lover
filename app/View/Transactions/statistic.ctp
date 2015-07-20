<div class="actions">
    <h2> Actions</h2>
    <ul>
        <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index')); ?></li>
    </ul>
</div>
<div class="statistic form">
    <h2>Overview</h2>
    <table>
        <tr>
            <th>Balance</th>
            <td><?php echo $currentMoney + $expense - $income . ' (VND)'; ?></td>
            <td></td>
        </tr>
        <tr>
            <th>Income Money</th>
            <td><?php echo $income . '(VND)'; ?></td>
            <td><?php echo $income / ($income + $expense) * 100 . '%'; ?></td>
        </tr>
        <tr>
            <th>Expense Money</th>
            <td><?php echo $expense . '(VND)'; ?></td>
            <td><?php echo $expense / ($income + $expense) * 100 . '%'; ?></td>
        </tr>
        <tr>
            <th>Current Money</th>
            <td><?php echo $currentMoney . '(VND)'; ?></td>
            <td></td>
        </tr>

    </table>
</div>
<div class="statistic form">
    <h2>Detail</h2>
    <div style="float: right; height: 100px" >
        <?php echo $this->Form->create(); ?>
        <p style="width: 400px"> form month    
            <?php echo $this->Form->month('form'); ?> 
            to month 
            <?php echo $this->Form->month('to'); ?>
        </p>
        <div style="float: right" > <?php echo $this->Form->end('Submit'); ?> </div>
    </div>
    <?php if(isset($sumIncome)): ?>
    <table>
        <tr>
            <th>Income Money <?php echo $sumIncome .'(VND)'; ?> </th>
            <?php if(!empty($cates)): ?> 
            <td> <?php foreach ($cates as $cate): ?>
                <?php if($cate['purpose'] == 1): ?>
                <p><?php echo $cate['name'] . ':' . $cate['amount'] . '(VND)  ' . $cate['amount']/$sumIncome*100 . '%' ?></p>
                <?php endif; ?>
                <?php endforeach; ?>
            </td>
            <?php endif; ?>
        </tr>
        <tr>
            <th>Expense Money <?php echo $sumExpense .'(VND)'; ?> </th>
            <?php if(!empty($cates)): ?>
            <td> <?php foreach ($cates as $cate): ?>
                <?php if($cate['purpose'] == 0): ?>
                <p><?php echo $cate['name'] . ':' . $cate['amount'] . '(VND)  ' . $cate['amount']/$sumExpense*100 . '%' ?></p>
                <?php endif; ?>
                <?php endforeach; ?>
            </td>
            <?php endif; ?>
        </tr>
    </table>
    <?php endif; ?>
</div>