<div class="row">
    <div class="col-md-3">
        <h3>Actions</h3>
        <ul>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'view')); ?></li>
        </ul>
    </div>
    <div class="col-md-6">
        <?php
        echo $this->Form->create('Wallet', array(
            'inputDefaults' => array(
                'div' => array(
                    'class' => 'form-group',
                ),
            ),
        ));
        ?>
        <fieldset>
            <legend>New Wallet</legend>
            <?php
            echo $this->Form->input('name', array(
                'label'    => 'Wallet Name',
                'required' => false,
                'class'    => 'form-control',
            ));
            echo $this->Form->input('balance', array(
                'required' => false,
                'class'    => 'form-control',
            ));
            echo $this->Form->end(array(
                'label' => 'Submit',
                'class' => 'btn btn-primary',
            ));
            ?>
        </fieldset>

    </div>
</div>