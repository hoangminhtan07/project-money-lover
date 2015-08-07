<div class="row">
    <div class="col-md-2">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Html->link('New Category', array('controller' => 'categories', 'action' => 'add', 'full_base' => true)); ?></li>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index', 'full_base' => true)); ?></li>
        </ul>
    </div>
    <div class="col-md-9">
        <h3 class="text-center">Categories</h3>
        <div class="col-md-6">
            <h4 class="text-center">Spent</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Note</th>
                        <th class="actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categoriesSpent as $category): ?>
                        <tr>
                            <td><?php echo $category['Category']['name']; ?></td>
                            <td><?php echo $category['Category']['note']; ?></td>
                            <td class="actions">
                                <?php
                                echo $this->Html->link(
                                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit')), array(
                                    'action' => 'edit', $category['Category']['id']), array(
                                    'class'  => 'btn btn-warning',
                                    'escape' => false,
                                ));
                                ?>
                                <?php
                                echo $this->Html->link(
                                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-trash')), array(
                                    'action' => 'delete', $category['Category']['id']), array(
                                    'class'  => 'btn btn-danger',
                                    'escape' => false,
                                ));
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h4 class="text-center">Earned</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Note</th>
                        <th class="actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categoriesEarned as $category): ?>
                        <tr>
                            <td><?php echo $category['Category']['name']; ?></td>
                            <td><?php echo $category['Category']['note']; ?></td>
                            <td class="actions">
                                <?php
                                echo $this->Html->link(
                                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-edit')), array(
                                    'action' => 'edit', $category['Category']['id']), array(
                                    'class'  => 'btn btn-warning',
                                    'escape' => false,
                                ));
                                ?>
                                <?php
                                echo $this->Html->link(
                                        $this->Html->tag('i', '', array('class' => 'glyphicon glyphicon-trash')), array(
                                    'action' => 'delete', $category['Category']['id']), array(
                                    'class'  => 'btn btn-danger',
                                    'escape' => false,
                                ));
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
