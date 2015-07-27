<div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6" style="margin-top: 88px">
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
        <div class="form-group">
            <label class="control-label col-xs-2">Username</label>        
            <div class="col-xs-10">
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
            <label class="control-label col-xs-2">Password</label>        
            <div class="col-xs-10">
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
            <div class="col-xs-offset-2 col-xs-10">
                <?php
                echo $this->Form->input('rememberMe', array(
                    'type'  => 'checkbox',
                    'div'   => false,
                    'label' => false,
                    'after' => '<span>Remember Me</span>',
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                <?php
                echo $this->Form->input('Login', array(
                    'type'  => 'submit',
                    'div'   => false,
                    'label' => false,
                    'class' => 'btn btn-primary',
                    'after' => $this->Html->link('Forgot password?', array('action' => 'forgotPassword')),
                ));
                ?>
            </div>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>