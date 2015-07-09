<div class="Category form">
    <?php echo $this->Form->create('Category'); ?>
    <fieldset>
        <legend>Edit Category</legend>
        <?php
        echo $this->Form->input('name');
        echo $this->Form->input('purpose', array(
            'options' => array(
                'Purpose' => array(
                    '0' => 'Spent', '1' => 'Earned')),
            'label'   => '',
            'class'   => 'scale',
                )
        );
        echo $this->Form->input('note');
        ?>
    </fieldset>
    <?php echo $this->Form->end('Submit'); ?>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('Back', array('action' => 'index')); ?></li>
    </ul>
</div>