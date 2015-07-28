<div class="row">
    <div class="col-md-3">
        <h3>Actions</h3>
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
            <?php
            echo $this->Form->input('current_password', array(
                'type'     => 'password',
                'required' => false,
                'label'    => 'Current Password',
                'class'    => 'form-control',
            ));
            echo $this->Form->input('password', array(
                'required' => false,
                'label'    => 'New Password',
                'class'    => 'form-control',
            ));
            echo $this->Form->input('retype_password', array(
                'required' => false,
                'type'     => 'password',
                'label'    => 'Retype Password',
                'class'    => 'form-control',
            ));
            ?>
            <?php
            echo $this->Form->end(array(
                'label' => 'Submit',
                'class' => 'btn btn-primary',
            ));
            ?>
        </fieldset>
    </div>
</div>