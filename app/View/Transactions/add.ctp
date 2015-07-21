<div class="Transaction form">
    <?php echo $this->Form->create(); ?>
    <fieldset>
        <legend>Add Transaction</legend>
        <?php
        echo $this->Form->input('categorySpentId', array(
            'options' => $listCategorySpent,
            'empty'   => 'Null',
            'label'   => 'Spent Purpose',
            'class'   => 'scale',
        ));
        echo $this->Form->input('categoryEarnedId', array(
            'options' => $listCategoryEarned,
            'empty'   => 'Null',
            'label'   => 'Earned Purpose',
            'class'   => 'scale'
        ));
        echo $this->Form->input('amount', array(
            'required' => false,
        ));
        echo $this->Form->input('note');
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>
<div class="actions">
    <ul>
        <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index')) ?></li>
    </ul>
</div>