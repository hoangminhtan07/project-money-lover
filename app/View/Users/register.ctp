<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
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
            <legend>Register</legend>
            <div class="form-group">
                <label class="control-label col-xs-3">Username</label>        
                <div class="col-xs-7">
                    <?php
                    echo $this->Form->input('username', array(
                        'class'    => 'form-control',
                        'div'      => false,
                        'label'    => false,
                        'required' => false,
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">Email</label>        
                <div class="col-xs-7">
                    <?php
                    echo $this->Form->input('email', array(
                        'class'    => 'form-control',
                        'div'      => false,
                        'label'    => false,
                        'required' => false,
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">Password</label>        
                <div class="col-xs-7">
                    <?php
                    echo $this->Form->input('password', array(
                        'class'    => 'form-control',
                        'div'      => false,
                        'label'    => false,
                        'required' => false,
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-xs-3">Retype Password</label>        
                <div class="col-xs-7">
                    <?php
                    echo $this->Form->input('retype_password', array(
                        'class'    => 'form-control',
                        'div'      => false,
                        'label'    => false,
                        'required' => false,
                        'type'     => 'password',
                    ));
                    ?>
                </div>
            </div>
            <div class="col-xs-offset-3">
                <?php
                echo $this->Form->end(array(
                    'label' => 'Register',
                    'class' => 'btn btn-primary',
                ));
                ?>
            </div>
        </fieldset>
    </div>
</div>