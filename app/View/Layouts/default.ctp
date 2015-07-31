<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion     = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
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
        echo $this->Html->script('formValidation');
        echo $this->Html->script('frameworkBootstrap');
        echo $this->Html->css('bootstrap.min');
        echo $this->Html->css('formValidation');
        echo $this->Html->css('web.cake');
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body>
        <header>
            <h1><?php echo 'Project Money-Lover'; ?></h1>
        </header>
        <div class="container">
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
            <div id="footer">
                <?php
                echo $this->Html->link(
                        $this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')), 'http://www.cakephp.org/', array('target' => '_blank', 'escape' => false, 'id' => 'cake-powered')
                );
                ?>
                <p>
                    <?php echo $cakeVersion; ?>
                </p>
            </div>
            <footer>
                <p class="foot"><?php echo 'Hayate07'; ?></p>
            </footer>
        </div>
    </body>
</html>
