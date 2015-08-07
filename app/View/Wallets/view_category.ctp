<?php echo $this->Html->script('WalletsJs/myJs'); ?>
<?php echo $this->Html->script('WalletsJs/jquery-ui-1.10.1.min'); ?>
<?php echo $this->Html->css('jquery-ui-1.10.1'); ?>
<?php echo $this->Html->css('nigran.datepicker'); ?>

<div class="row">
    <div class="col-md-2">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index')); ?></li>
        </ul>
    </div>
    <div class="col-md-9">
        <h2 class="text-center">View By Category</h2>
        <br>
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <select id="choose" class="form-control input-sm">
                <option value="viewDay" <?php
                if (strpos(Router::url(), 'viewDay') == true) : echo 'selected';
                endif;
                ?> >View By Day</option>
                <option value="viewCategory" <?php
                if (strpos(Router::url(), 'viewCategory') == true) : echo 'selected';
                endif;
                ?> >View By Category</option>
            </select>
        </div>
        <form name="dateForm" class="form-inline col-md-6">
            <div class="form-group">
                <input id="from_day" name="from_day" type="text" class = "form-control date_input input-sm" placeholder="From day">
            </div>
            <div class="form-group">
                <input id="to_day" name="to_day" type="text" class = "form-control date_input input-sm" placeholder="To day">
            </div>
            <button class="btn btn-info btn-sm" type="button">Go</button>
        </form>        <?php
        $name = '';
        $flag = 0;
        foreach ($transByCategory as $tran):
            $nameCate = $tran['Category']['name'];
            if ($nameCate != $name):
                if ($flag == 1):
                    ?>
                    </tbody>
                    </table>
                    <br>
                    <?php
                endif;
                $name = $nameCate;
                $flag = 1;
                ?>
                <h4><?php echo $name; ?></h4>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date Created</th>
                            <th>Purpose</th>
                            <th>Amount</th>
                            <th style="width: 300px">Note</th>
                        </tr>
                    </thead>
                <?php endif; ?>

                <tbody>
                    <tr>
                        <td><?php echo $tran['Transaction']['created']; ?></td>
                        <?php if ($tran['Category']['purpose'] == 0): ?>
                            <td><?php echo 'Spent'; ?></td>
                        <?php else: ?>
                            <td><?php echo 'Earned'; ?></td>
                        <?php endif; ?>
                        <td><?php echo $tran['Transaction']['amount']; ?></td>
                        <td><?php echo $tran['Transaction']['note']; ?></td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
