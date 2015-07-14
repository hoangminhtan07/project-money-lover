<div class="Transfer form">
    <fieldset>
        <legend>Transfer money</legend>
        <?php
        echo $this->Form->create();
        echo $this->Form->input('fromWallet', array(
            'options' => array(
                'Wallet Name' => $list),
            'label'   => 'Form',
            'class'   => 'scale',
        ));
        echo $this->Form->input('toWallet', array(
            'options' => array(
                'Wallet Name' => $list),
            'label'   => 'To',
            'class'   => 'scale'
        ));
        echo $this->Form->input('amounts');
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('Back',array('action' => 'view')); ?></li>
    </ul>
</div>