<?php
echo $this->Html->script('transactions');
echo $this->Html->script('jquery-ui-1.10.1.min');
echo $this->Html->css('jquery-ui-1.10.1');
echo $this->Html->css('nigran.datepicker');
?>

<div class="row">
    <div class="col-md-2">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index')) ?></li>
        </ul>
    </div>
    <div class="col-md-8">
        <?php
        echo $this->Form->create('Transaction', array(
            'inputDefaults' => array(
                'div' => array(
                    'class' => 'form-group',
                ),
            ),
            'class'         => 'form-horizontal',
        ));
        ?>
        <fieldset>
            <legend>Add Transaction</legend>
            <div class="form-group">
                <label class="control-label col-xs-3">Spent Purpose</label>
                <div class="col-xs-3">
                    <?php
                    echo $this->Form->input('categorySpentId', array(
                        'options' => $listCategorySpent,
                        'empty'   => 'Null',
                        'label'   => false,
                        'class'   => 'form-control',
                    ));
                    ?>
                </div>
                <label class="control-label col-xs-2">Date</label>
                <div class=" col-xs-3">
                    <?php
                    echo $this->Form->input('created', array(
                        'placeholder' => 'Input date',
                        'type'        => 'text',
                        'required'    => false,
                        'label'       => false,
                        'class'       => 'form-control date_input',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">Earned Purpose</label>
                <div class="col-xs-3">
                    <?php
                    echo $this->Form->input('categoryEarnedId', array(
                        'options' => $listCategoryEarned,
                        'empty'   => 'Null',
                        'label'   => false,
                        'class'   => 'form-control'
                    ));
                    ?>
                </div>
                <label class="control-label col-xs-2">Amount</label>
                <div class=" col-xs-3">
                    <?php
                    echo $this->Form->input('amount', array(
                        'required' => false,
                        'label'    => false,
                        'class'    => 'form-control',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">Note</label>
                <div class=" col-xs-8">
                    <?php
                    echo $this->Form->input('note', array(
                        'label' => false,
                        'class' => 'form-control',
                        'rows'  => 4,
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