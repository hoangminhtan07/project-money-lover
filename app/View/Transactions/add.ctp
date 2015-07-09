<div class="Transaction form">
    <?php echo $this->Form->create(); ?>
    <fieldset>
        <legend>Add Transaction</legend>
        <?php
        echo $this->Form->input('categorySpentId', array(
            'options' => array(
                'Category' => $listCategorySpent,
            ),
            'empty'   => 'Null',
            'label'   => 'Spent Purpose',
            'class'   => 'scale',
        ));
        echo $this->Form->input('categoryEarnedId', array(
            'options' => array(
                'Category' => $listCategoryEarned,
            ),
            'empty'   => 'Null',
            'label'   => 'Earned Purpose',
            'class'   => 'scale'
        ));
        echo $this->Form->input('amount');
        echo $this->Form->input('note');
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>