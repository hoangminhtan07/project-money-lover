Dear <?php echo $email; ?>
<p>Please click the follow link to reset your account password in Server Money Lover.</p>
<p>
    <?php
    echo $this->Html->link('Resset link', array(
        'controller' => 'users',
        'action'     => 'resetPassword',
        $email,
        $token,
        'full_base'  => true,
    ));
    ?>
</p>