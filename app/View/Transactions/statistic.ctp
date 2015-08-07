<?php
echo $this->Html->script('TransactionsJs/myJs');
echo $this->Html->script('TransactionsJs/jquery-ui-1.10.1.min');
echo $this->Html->css('jquery-ui-1.10.1');
echo $this->Html->css('nigran.datepicker');
?>
<div class="row">
    <div class="col-md-2">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index')); ?></li>
        </ul>
    </div>
    <div class="col-md-9">
        <h3>Overview</h3>
        <table class="table table-hover">
            <tr>
                <th>Balance</th>
                <td><?php echo $currentMoney - $expense - $income . ' (VND)'; ?></td>
                <td></td>
            </tr>
            <tr>
                <th>Income Money</th>
                <td><?php echo $income . '(VND)'; ?></td>
                <td><?php echo round($income / ($income - $expense) * 100, 2) . '%'; ?></td>
            </tr>
            <tr>
                <th>Expense Money</th>
                <td><?php echo abs($expense) . '(VND)'; ?></td>
                <td><?php echo round(-$expense / ($income - $expense) * 100, 2) . '%'; ?></td>
            </tr>
            <tr>
                <th>Current Money</th>
                <td><?php echo $currentMoney . '(VND)'; ?></td>
                <td></td>
            </tr>
        </table>
    </div>
    <div class="col-md-offset-2 col-md-9">
        <div class="col-md-6"><h3>Detail</h3></div>
        <?php
        echo $this->Form->create('Static', array(
            'inputDefaults' => array(
                'div' => array(
                    'class' => 'form-group',
                ),
            ),
            'class'         => 'form-horizontal col-md-6',
            'style'         => 'margin-top: 30px',
        ));
        ?>
        <div class="form-group">
            <div class=" col-md-4">
                <?php
                echo $this->Form->input('fdate', array(
                    'placeholder' => 'From date',
                    'type'        => 'text',
                    'required'    => false,
                    'label'       => false,
                    'class'       => 'form-control date_input input-sm',
                ));
                ?>
            </div>
            <div class=" col-md-4">
                <?php
                echo $this->Form->input('tdate', array(
                    'placeholder' => 'To date',
                    'type'        => 'text',
                    'required'    => false,
                    'label'       => false,
                    'class'       => 'form-control date_input input-sm',
                ));
                ?>
            </div>
            <div class="col-md-1">
                <?php
                echo $this->Form->end(array(
                    'label' => 'Submit',
                    'class' => 'btn btn-primary btn-sm',
                ));
                ?>
            </div>
        </div>

        <?php if (isset($sumIncome)): ?>
            <table class="table table-hover">
                <tr>
                    <th>Income Money <?php echo $sumIncome . '(VND)'; ?> </th>
                    <?php if (!empty($cates)): ?> 
                        <td> <?php foreach ($cates as $cate): ?>
                                <?php if ($cate['purpose'] == 1): ?>
                                    <p><?php echo $cate['name'] . ':' . $cate['amount'] . '(VND)  ' . round($cate['amount'] / $sumIncome * 100, 2) . '%' ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    <?php endif; ?>
                </tr>
                <tr>
                    <th>Expense Money <?php echo abs($sumExpense) . '(VND)'; ?> </th>
                    <?php if (!empty($cates)): ?>
                        <td> <?php foreach ($cates as $cate): ?>
                                <?php if ($cate['purpose'] == 0): ?>
                                    <p><?php echo $cate['name'] . ':' . abs($cate['amount']) . '(VND)  ' . round($cate['amount'] / $sumExpense * 100, 2) . '%' ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </td>
                    <?php endif; ?>
                </tr>
            </table>
        <?php endif; ?>
    </div>
</div>