<div class="row">
    <div class="col-md-3">
        <h3>Actions</h3>
        <ul>
            <li><?php echo $this->Html->link('Back', array('action' => 'index')); ?></li>
        </ul>
    </div>
    <div class="col-md-6">
        <?php
        echo $this->Form->create('Category', array(
            'inputDefaults' => array(
                'div' => array(
                    'class' => 'form-group',
                ),
            ),
            'class'         => 'form-horizontal',
        ));
        ?>
        <fieldset>
            <legend>New Category</legend>
            <div class="form-group">
                <label class="control-label col-xs-3">Category Name</label>        
                <div class="col-xs-6">
                    <?php
                    echo $this->Form->input('name', array(
                        'class'    => 'form-control',
                        'label'    => false,
                        'required' => false,
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">Purpose</label>        
                <div class="col-xs-3">
                    <?php
                    echo $this->Form->input('purpose', array(
                        'options' => array(
                            'Purpose' => array(
                                '0' => 'Spent', '1' => 'Earned')),
                        'label'   => false,
                        'class'   => 'form-control',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">Note</label>
                <div class=" col-xs-6">
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