<?php echo $this->Html->script('myJs'); ?>
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
                    'class'       => 'form-control',
                    'div'         => false,
                    'label'       => false,
                    'required'    => false,
                    'placeholder' => 'Username',
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-xs-2">Password</label>        
            <div class="col-xs-10">
                <?php
                echo $this->Form->input('password', array(
                    'class'       => 'form-control',
                    'div'         => false,
                    'label'       => false,
                    'required'    => false,
                    'placeholder' => 'Password',
                ));
                ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-offset-2 col-xs-10">
                <div class="col-xs-8">
                    <?php
                    echo $this->Form->input('rememberMe', array(
                        'type'  => 'checkbox',
                        'div'   => false,
                        'label' => false,
                        'after' => '<span>  Remember Me</span>',
                    ));
                    ?>
                </div>
                <div class="col-xs-4">
                    <?php
                    echo $this->Html->link('Forgot password?', "#", array(
                        'data-toggle' => 'modal',
                        'data-target' => '#forgotPasswordModal'
                    ));
                    ?>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="col-xs-2"> </div>
            <div class="col-xs-10">
                <?php
                echo $this->Form->end(array(
                    'label' => 'Login',
                    'class' => 'btn btn-primary',
                ));
                ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h3 class="modal-title text-center">Forgot Password?</h3>
            </div>
            <div class="modal-body">
                <p>Enter your email address to reset your password:</p>
                <form id="forgotPasswordForm" method="post">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control input-lg" placeholder="E-mail Address" name="email" type="text">
                        </div>
                        <div class="text-right">
                            <?php
                            echo $this->Form->end(array(
                                'label'       => 'Submit',
                                'action'      => 'forgotPassword',
                                'class'       => 'btn btn-primary data',
                                'data-action' => Router::url(
                                        array('action' => 'forgotPassword')
                                ),
                                'escape'      => false,
                            ));
                            ?>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
