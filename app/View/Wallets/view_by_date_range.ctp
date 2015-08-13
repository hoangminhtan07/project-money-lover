<?php
echo $this->Html->script('wallets');
echo $this->Html->script('jquery-ui-1.10.1.min');
echo $this->Html->css('jquery-ui-1.10.1');
echo $this->Html->css('nigran.datepicker');
?>

<div class="row">
    <div class="col-md-2">
        <h3>Menu</h3>
        <ul>
            <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index')); ?></li>
        </ul>
    </div>
    <div class="col-md-9">
        <h2 class="text-center">View By Date Range</h2>
        <br>    
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <select id="choose" class="form-control input-sm">
                <option value="00" <?php
                if (strpos(Router::url(), 'cate-00') !== false) : echo 'selected';
                endif;
                ?> >All</option>

                <optgroup label="Spent">
                    <?php foreach ($listCateOfWallet as $cateId => $cateName): ?>
                        <?php if ($cateName['1'] == false): ?>
                            <option value="<?php echo $cateId ?>" <?php
                            if (strpos(Router::url(), "cate-$cateId") !== false) : echo 'selected';
                            endif;
                            ?> ><?php echo $cateName['0']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                </optgroup>
                <optgroup label="Earned">
                    <?php foreach ($listCateOfWallet as $cateId => $cateName): ?>
                        <?php if ($cateName['1'] == true): ?>
                            <option value="<?php echo $cateId ?>" <?php
                            if (strpos(Router::url(), "cate-$cateId") !== false) : echo 'selected';
                            endif;
                            ?> ><?php echo $cateName['0']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                </optgroup>

            </select>
        </div>
        <form id="dateForm" class="form-inline col-md-6">
            <div class="form-group">
                <input value="<?php
                echo $fdate;
                ?>" id="from_date" name="from_date" type="text" class = "form-control date_input input-sm" placeholder="From date">
            </div>
            <div class="form-group">
                <input value="<?php
                echo $tdate;
                ?>" id="to_date" name="to_date" type="text" class = "form-control date_input input-sm" placeholder="To date">
            </div>
            <button class="btn btn-info btn-sm" type="button">Go</button>
        </form>
        <?php
        $firstDate = '0000-00-00';
        $flag      = 0;
        foreach ($transByDate as $tran):
            $date = new DateTime($tran['Transaction']['created']);
            $date = $date->format('Y-m-d');
            if ($date > $firstDate):
                if ($flag == 1):
                    ?>
                    </tbody>
                    </table>
                    <br>
                    <?php
                endif;
                $firstDate = $date;
                $flag      = 1;
                ?>
                <h4><?php echo $firstDate; ?></h4>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Purpose</th>
                            <th>Amount</th>
                            <th style="width: 300px">Note</th>
                        </tr>
                    </thead>
                <?php endif; ?>

                <tbody>
                    <tr>
                        <td><?php echo $tran['Category']['name']; ?></td>
                        <?php if ($tran['Category']['purpose'] == 0): ?>
                            <td><?php echo 'Spent'; ?></td>
                        <?php else: ?>
                            <td><?php echo 'Earned'; ?></td>
                        <?php endif; ?>
                        <td><?php echo number_format(abs($tran['Transaction']['amount'])); ?></td>
                        <td><?php echo $tran['Transaction']['note']; ?></td>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
