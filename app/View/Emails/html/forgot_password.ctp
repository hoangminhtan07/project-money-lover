Dear <?php echo $email; ?>
<p>Please click the follow link to resset your account password in Server Money Lover.</p>
<p>
    <?php
    echo $this->Html->link('Resset link', array(
        'controller' => 'users',
        'action'     => 'resset_password',
        $email,
        $token,
        'full_base'  => true,
    ));
    ?>
</p>