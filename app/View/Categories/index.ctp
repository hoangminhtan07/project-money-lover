<div class="Categories index">
    <h2>Categories</h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th>Name</th>
            <th>Purpose</th>
            <th>Note</th>
            <th class="actions">Actions</th>
        </tr>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?php echo $category['Category']['name']; ?>&nbsp;</td>
                <td><?php
                    if ($category['Category']['purpose'] == 0) {
                        echo 'Spent';
                    } else {
                        echo 'Earned';
                    }
                    ?>&nbsp;</td>
                <td><?php echo $category['Category']['note']; ?>&nbsp;</td>
                <td class="actions">
                    <?php echo $this->Html->link('Edit', array('action' => 'edit', $category['Category']['id'])); ?>
                    <?php echo $this->Form->postlink('Delete', array('action' => 'delete', $category['Category']['id']), array('confirm' => 'Are you sure?')); ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<div class="actions">
    <h3>Actions</h3>
    <ul>
        <li><?php echo $this->Html->link('New Category', array('controller' => 'categories', 'action' => 'add', 'full_base' => true)); ?></li>
        <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index', 'full_base' => true)); ?></li>
    </ul>
</div>