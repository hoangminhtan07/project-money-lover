<?php echo $this->Html->script('WalletsJs/myJs'); ?>
<div class="row">
    <div class="col-md-3">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Html->link('New Wallet', array('controller' => 'wallets', 'action' => 'add')); ?></li>
            <li><?php echo $this->Html->link('Transfer money', array('controller' => 'wallets', 'action' => 'transfer')); ?></li>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index')); ?></li>
        </ul>
    </div>
    <div class="col-md-6">
        <h3 class="text-center">Wallets</h3>
        <table class="table table-hover text-center">
            <thead>
                <tr>

                    <th class="text-center">Name</th>
                    <th class="text-center">Balance</th>
                    <th class="text-center">Created</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wallets as $wallet): ?>
                <td><?php echo $wallet['Wallet']['name']; ?>&nbsp;</td>
                <td><?php echo $wallet['Wallet']['balance']; ?>&nbsp;</td>
                <td><?php echo $wallet['Wallet']['created']; ?>&nbsp;</td>
                <td class="actions">
                    <?php
                    echo $this->Html->link(
                            $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit')), array(
                        'controller' => 'wallets', 'action'     => 'edit', $wallet['Wallet']['id']), array(
                        'class'  => 'btn btn-warning',
                        'escape' => false,
                    ));
                    ?>
                    <?php
                    echo $this->Form->postlink(
                            $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-trash')), array(
                        'controller' => 'wallets', 'action'     => 'delete', $wallet['Wallet']['id']), array(
                        'confirm' => 'Are you sure?',
                        'class'   => 'btn btn-danger',
                        'escape'  => false,
                    ));
                    ?>
                    <?php
                    echo $this->Html->link(
                            $this->Html->tag('i', ' Set_current', array('class' => 'glyphicon glyphicon-piggy-bank')), array(
                        'controller' => 'users', 'action'     => 'setCurrentWallet', $wallet['Wallet']['id']), array(
                        'class'  => 'btn btn-success',
                        'escape' => false,
                    ));
                    ?>
                </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>