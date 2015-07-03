<?php if ($logged_in): ?>
    <div class="users index">
    </div>
    <div class="actions">
        <h3>Actions</h3>
        <ul>
            <li><?php echo $this->Html->link('View All Wallets', array('controller'=>'wallets','action' => 'view')); ?></li>
            <li><?php echo $this->Html->link('Back', array('controller' => 'users', 'action' => 'index', 'full_base' => true)); ?></li>
        </ul>
    </div>
<?php endif; ?>
F