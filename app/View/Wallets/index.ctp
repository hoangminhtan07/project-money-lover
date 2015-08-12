<?php echo $this->Html->script('WalletsJs/myJs'); ?>

<div class="row">
    <div class="col-md-2">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Html->link('Edit User', array('controller' => 'users', 'action' => 'edit')); ?></li>
            <li><?php echo $this->Html->link('View All Wallets', array('controller' => 'wallets', 'action' => 'view')); ?></li>
            <li><?php echo $this->Html->link('Categories', array('controller' => 'categories', 'action' => 'index')); ?></li>
            <?php if (!empty($transactions)): ?>
                <li><?php echo $this->Html->link('Statistics', array('controller' => 'transactions', 'action' => 'statistic')); ?></li>
            <?php endif; ?>
            <li><?php echo $this->Html->link('Back', array('controller' => 'users', 'action' => 'index')); ?></li>
        </ul>
    </div>
    <div class="col-md-9">
        <h2 class="text-center"> <?php echo $wallet['Wallet']['name'] . ':' . ' ' . 'Money :' . $wallet['Wallet']['balance'] . '(VND)'; ?></h2>
        <div class="col-md-3">
            <?php
            echo $this->Form->button('Add Transaction', array(
                'controller' => 'transactions',
                'action'     => 'add',
                'type'       => 'button',
                'class'      => 'btn btn-success btn-xs',
                'name'       => 'addTran',
            ));
            ?>
            <?php
            echo $this->Html->link(
                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-plus')), array('controller' => 'transactions', 'action' => 'add'), array(
                'class'  => 'btn btn-success btn-xs',
                'escape' => false));
            ?>
        </div>

        <div class="col-md-offset-7 col-md-1">
            <?php
            echo $this->Html->link(
                    $this->Html->tag('i', ' Detail ', array('class' => 'glyphicon glyphicon-menu-right')), array('action' => 'viewByDateRange', 'cate-00'), array(
                'class'  => 'btn btn-success btn-xs',
                'escape' => false));
            ?>
        </div>

        <table class="table table-hover" style="height: 190px">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Purpose</th>
                    <th>Amount</th>
                    <th>Note</th>
                    <th>Created</th>
                    <th>Modified</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td> <?php echo $transaction['Category']['name']; ?></td>
                        <?php if ($transaction['Category']['purpose'] == 0): ?>
                            <td><?php echo 'Spent'; ?></td>
                        <?php else: ?>
                            <td><?php echo 'Earned'; ?></td>
                        <?php endif; ?>
                        <td><?php echo number_format(abs($transaction['Transaction']['amount'])); ?></td>
                        <td><?php echo $transaction['Transaction']['note']; ?></td>
                        <td><?php echo $transaction['Transaction']['created']; ?></td>
                        <td><?php echo $transaction['Transaction']['modified']; ?></td>
                        <td class="actions">
                            <?php
                            echo $this->Html->link(
                                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit')), array(
                                'controller' => 'transactions', 'action'     => 'edit', $transaction['Transaction']['id']), array(
                                'class'  => 'btn btn-warning',
                                'escape' => false,
                            ));
                            ?>
                            <?php
                            echo $this->Form->postlink(
                                    $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-trash')), array(
                                'controller' => 'transactions', 'action'     => 'delete', $transaction['Transaction']['id']), array(
                                'confirm' => 'Are you sure?',
                                'class'   => 'btn btn-danger',
                                'escape'  => false,
                            ));
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        $paginator = $this->Paginator;
        // pagination section
        echo "<div class='pager'>";

        // the 'first' page button
        echo $paginator->first("First", array(
            'separator'    => '',
            'currentClass' => 'active',
            'tag'          => 'li',
            'after'        => '</ul>',
            'modulus'      => 2,
        ));

        if ($paginator->hasPrev()) {
            echo $paginator->prev("<<", array(
                'class' => false,
                'tag'   => 'li',
            ));
        }

        // the 'number' page buttons
        echo $paginator->numbers(array(
            'separator'    => '',
            'currentClass' => 'active',
            'tag'          => 'li',
            'modulus'      => 2,
        ));

        // for the 'next' button
        if ($paginator->hasNext()) {
            echo $paginator->next(">>", array(
                'class' => false,
                'tag'   => 'li',
            ));
        }

        // the 'last' page button
        echo $paginator->last("Last", array(
            'tag' => 'li',
        ));

        echo "</div>";
        ?>
    </div>
</div>
