<div class="row">
    <div class="col-md-offset-3 col-md-6">
        <?php
        echo $this->Form->create('User', array(
            'inputDefaults' => array(
                'div' => array(
                    'class' => 'form-group',
                ),
            ),
            'class'         => 'form-group',
        ));
        ?>
        <fieldset>
            <legend>New Password</legend>
            <?php
            echo $this->Form->input('password', array(
                'required' => false,
                'class'    => 'form-control',
            ));
            echo $this->Form->input('retype_password', array(
                'type'     => 'password',
                'required' => false,
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
