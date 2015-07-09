<div class="wallets form">
    <?php echo $this->Form->create('Wallet'); ?>
    <fieldset>
        <legend>New Wallet</legend>
        <?php
        echo $this->Form->input('name');
        echo $this->Form->input('balance');
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'view')); ?></li>
    </ul>
</div>