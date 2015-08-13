<div class="row">
    <div class="col-md-3">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Html->link('Back', array('action' => 'edit')); ?></li>
        </ul>
    </div>
    <div class="col-md-6">
        <?php
        echo $this->Form->create('User', array(
            'inputDefaults' => array(
                'div' => array(
                    'class' => 'form-group',
                ),
            ),
            'class'         => 'form-horizontal',
        ));
        ?>
        <fieldset>
            <legend>Change Password</legend>
            <div class="form-group">
                <label class="control-label col-md-3">Current Password</label>
                <div class="col-md-9">
                    <?php
                    echo $this->Form->input('current_password', array(
                        'type'     => 'password',
                        'required' => false,
                        'label'    => false,
                        'class'    => 'form-control',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3">New Password</label>
                <div class="col-md-9">
                    <?php
                    echo $this->Form->input('password', array(
                        'required' => false,
                        'label'    => false,
                        'class'    => 'form-control',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-3">Retype Password</label>
                <div class="col-md-9">
                    <?php
                    echo $this->Form->input('retype_password', array(
                        'required' => false,
                        'type'     => 'password',
                        'label'    => false,
                        'class'    => 'form-control',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-offset-3">
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