<div class="user form">
<?php echo $this->Session->flash('auth'); ?>
<?php echo $this->Form->creat('User'); ?>
    <fieldset>
        <legend>
            <?php echo __('Enter your name'); ?>
        </legend>
        <?php echo $this->Form-input('name');
              echo $this->Form->input('password');
        ?>
    </fieldset>
<?php echo $this->Form->end(__('Login')); ?>
</div>
<span class="error"><?php echo $error; ?></span>