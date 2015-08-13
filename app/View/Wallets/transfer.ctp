<div class="row">
    <div class="col-md-3">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Html->link('Back', array('action' => 'view')); ?></li>
        </ul>
    </div>
    <div class="col-md-6">
        <fieldset>
            <legend>Transfer money</legend>
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
            <div class="form-group">
                <label class="control-label col-xs-3">Form</label>
                <div class="col-xs-2">
                    <?php
                    echo $this->Form->input('fromWallet', array(
                        'options' => array(
                            'Wallet Name' => $list),
                        'label'   => false,
                        'class'   => 'form-control',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">To</label>
                <div class="col-xs-2">
                    <?php
                    echo $this->Form->input('toWallet', array(
                        'options' => array(
                            'Wallet Name' => $list),
                        'label'   => false,
                        'class'   => 'form-control',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">Amounts</label>
                <div class="col-xs-8">
                    <?php
                    echo $this->Form->input('amounts', array(
                        'label' => false,
                        'class' => 'form-control',
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
            </div>
        </fieldset>
    </div>
</div>