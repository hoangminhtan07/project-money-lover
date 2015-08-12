<?php echo $this->Html->script('WalletsJs/myJs'); ?>
<div class="row">
    <div class="col-md-3">
        <h3>Menu</h3>
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
            'class'         => 'form-horizontal',
        ));
        ?>
        <fieldset>
            <legend>New Wallet</legend>
            <div class="form-group">
                <label class="control-label col-xs-3">Wallet Name</label>        
                <div class="col-xs-9">
                    <?php
                    echo $this->Form->input('name', array(
                        'label'    => false,
                        'required' => false,
                        'class'    => 'form-control',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">Balance</label>        
                <div class="col-xs-9">
                    <?php
                    echo $this->Form->input('balance', array(
                        'label'    => false,
                        'required' => false,
                        'class'    => 'form-control',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-offset-3">
                    <?php
                    echo $this->Form->end(array(
                        'label' => 'Submit',
                        'class' => 'btn btn-primary',
                    ));
                    ?>
                </div>
        </fieldset>
    </div>
</div>