<div class="row">
    <div class="col-md-3">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Form->postlink('Delete User', array('action' => 'delete'), array('confirm' => 'Are you sure?')); ?></li>
            <li><?php echo $this->Html->link('Change Password', array('action' => 'changePassword')); ?></li>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index')); ?></li>
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
            <legend>Edit User</legend>
            <div class="form-group">
                <label class="control-label col-xs-2">Username</label>        
                <div class="col-xs-10">
                    <?php
                    echo $this->Form->input('username', array(
                        'default'  => AuthComponent::user('username'),
                        'class'    => 'form-control',
                        'label'    => false,
                        'required' => false,
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-2">Username</label>        
                <div class="col-xs-10">
                    <?php
                    echo $this->Form->input('email', array(
                        'default'  => AuthComponent::user('email'),
                        'required' => false,
                        'class'    => 'form-control',
                        'label'    => false,
                        'type'     => 'text',
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-2"></div>
                <div class="col-xs-10">
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