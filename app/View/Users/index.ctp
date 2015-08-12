<?php echo $this->Html->script('UsersJs/myJs'); ?>
<h3 class="text-center">Welcome to Project Money Lover</h3>
<?php if (AuthComponent::user()): ?>
    <div class="text-center index-button">
        <?php
        echo $this->Html->link('My Wallet', array(
            'controller' => 'wallets',
            'action'     => 'index',
                ), array(
            'class' => 'button'
        ));
        ?>
    </div>
<?php endif; ?>