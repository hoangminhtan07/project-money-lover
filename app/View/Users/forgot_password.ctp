<?php echo $this->Html->script('UsersJs/myJs'); ?>
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
            <legend>Enter your email:</legend>
            <?php
            echo $this->Form->input('email', array(
                'required' => false,
                'type'     => 'text',
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
