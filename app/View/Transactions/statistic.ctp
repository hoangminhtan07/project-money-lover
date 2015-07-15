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
            <td><?php echo $income / ($income + $expense) *100 .'%' ; ?></td>
        </tr>
        <tr>
            <th>Expense Money</th>
            <td><?php echo $expense . '(VND)'; ?></td>
            <td><?php echo $expense / ($income + $expense) *100 .'%' ; ?></td>
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
    
    <table>
        <tr>
            <th>Balance</th>
            <td><?php echo ' (VND)'; ?></td>
            <td></td>
        </tr>
        <tr>
            <th>Income Money</th>
            <td><?php echo '(VND)'; ?></td>
            <td><?php echo '%' ; ?></td>
        </tr>
        <tr>
            <th>Expense Money</th>
            <td><?php echo  '(VND)'; ?></td>
            <td><?php echo  '%' ; ?></td>
        </tr>
        <tr>
            <th>Current Money</th>
            <td><?php echo '(VND)'; ?></td>
            <td></td>
        </tr>

    </table>
</div>