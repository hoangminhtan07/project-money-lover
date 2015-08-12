<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php echo 'Money-lover'; ?>
        </title>
        <link rel="shortcut icon" type="image/x-icon" href="<?php echo $this->webroot; ?>img/wallet.png">
        <?php
        echo $this->Html->script('jquery-1.11.3.min');
        echo $this->Html->script('bootstrap.min');
        echo $this->Html->script('mainJs');
        echo $this->Html->css('bootstrap.min');
        echo $this->Html->css('web.cake');
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body>
        <header>
            <a href="money.server.dev">
                <image id="img-head" src="<?php echo $this->webroot; ?>img/wallet-logo.png" alt="money.server.dev" class="img-logo">
            </a>
            <div id="head-text">Project Money-Lover</div>
        </header>
        <div id="leftBackground"></div>
        <nav id="navTop" class="navbar navbar-new navbar-default">
            <div class="container-fluid">
                <div>
                    <ul class="nav navbar-nav">
                        <?php
                        echo $this->Html->tag('li', $this->Html->link('&bnsp;', Router::fullBaseUrl(), array(
                                    'class' => 'navbar-header nav-home'
                        )));
                        ?>
                        <?php echo $this->Html->tag('li', $this->Html->link('User Edit', array('controller' => 'users', 'action' => 'edit'))); ?>
                        <?php echo $this->Html->tag('li', $this->Html->link('Add Tran', array('controller' => 'transactions', 'action' => 'add'))); ?>
                        <?php echo $this->Html->tag('li', $this->Html->link('Wallets', array('controller' => 'wallets', 'action' => 'view'))); ?>
                        <?php echo $this->Html->tag('li', $this->Html->link('Categories', array('controller' => 'categories', 'action' => 'index'))); ?>
                        <?php echo $this->Html->tag('li', $this->Html->link('Statistics', array('controller' => 'transactions', 'action' => 'statistic'))); ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container-fluid">
            <div id="content">
                <div style="text-align: right">
                    <?php if (AuthComponent::user()): ?>
                        Welcome <?php echo AuthComponent::user('username'); ?> <?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?>
                    <?php else: ?>
                        <?php echo $this->Html->link('Register', array('controller' => 'users', 'action' => 'register')); ?>
                        <?php echo ' ' ?>
                        <?php echo $this->Html->link('Login', array('controller' => 'users', 'action' => 'login')); ?>
                    <?php endif; ?>
                </div>

                <?php echo $this->Session->flash(); ?>
                <?php echo $this->Session->flash('auth'); ?>
                <?php echo $this->fetch('content'); ?>
            </div>
            <footer class="nav-up">
                <p class="foot"><?php echo 'Hayate07'; ?></p>
            </footer>
        </div>
    </body>
</html>
