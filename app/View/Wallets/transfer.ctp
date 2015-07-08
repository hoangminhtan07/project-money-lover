<?php

echo $this->Form->create();
echo $this->Form->input(
        'fromWallet', array(
    'options' => array('Wallet Name' => $list),
    'label'   => '',
    'class'   => 'scale'
        )
);
echo $this->Form->input(
        'toWallet', array(
    'options' => array('Wallet Name' => $list),
    'label'   => '',
    'class'   => 'scale'
        )
);
echo $this->Form->input('amounts');
echo $this->Form->end('Submit');
?>
