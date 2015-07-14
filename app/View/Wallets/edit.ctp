<div class="wallets form">
    <?php echo $this->Form->create('Wallet'); ?>
    <fieldset>
        <legend>Edit Wallet</legend>
        <?php
        echo $this->Form->input('name', array(
            'required' => false,
        ));
        echo $this->Form->input('balance', array(
            'required' => false,
        ));
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('Back', array('action' => 'view')); ?></li>
    </ul>
</div>