
<div class="view day">
    <h2> Date Range</h2>
    <?php foreach ($transactions as $date => $transaction): ?>
        <h4><?php echo $date; ?></h4>
        <table cellpading="0" cellspacing="0">
            <tr>
                <th>Category</th>
                <th>Purpose</th>
                <th>Amount</th>
                <th style="width: 300px">Note</th>
            </tr>
            <?php foreach ($transaction as $tran): ?>
                <tr>
                    <td><?php echo $tran['Category']['name']; ?></td>
                    <?php if ($tran['Category']['purpose'] == 0): ?>
                        <td><?php echo 'Spent'; ?></td>
                    <?php else: ?>
                        <td><?php echo 'Earned'; ?></td>
                    <?php endif; ?>
                    <td><?php echo $tran['Transaction']['amount']; ?></td>
                    <td><?php echo $tran['Transaction']['note']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>
    <?php endforeach; ?>
</div>

<div class="actions">
    <h3>Actions</h3>
    <ul>       
        <li><?php echo $this->Html->link('Back', array('controller' => 'wallets', 'action' => 'index', 'full_base' => true)); ?></li>
    </ul>
</div>

<?php
$paginator = $this->Paginator;

if ($trans) {

    //creating our table
    echo "<table>";

    echo "<tr>";
    echo "<th>" . 'Category' . "</th>";
    echo "<th>" . 'Purpose' . "</th>";
    echo "<th>" . 'Amount' . "</th>";
    echo "<th>" . 'Note' . "</th>";
    echo "</tr>";

    // loop through the tran's records
    foreach ($trans as $tran) {
        echo "<tr>";
        echo "<td>{$tran['Category']['name']}</td>";
        if ($tran['Category']['purpose'] == 0) {
            echo "<td>" . 'Spent' . "</td>";
        } else {
            echo "<td>" . 'Eanred' . "</td>";
        }
        echo "<td>{$tran['Transaction']['amount']}</td>";
        echo "<td>{$tran['Transaction']['note']}</td>";
        echo "</tr>";
    }

    echo "</table>";

    // pagination section
    echo "<div class='paging'>";

    // the 'first' page button
    echo $paginator->first("First");

    // 'prev' page button, 
    if ($paginator->hasPrev()) {
        echo $paginator->prev("Prev");
    }

    // the 'number' page buttons
    echo $paginator->numbers(array('modulus' => 2));

    // for the 'next' button
    if ($paginator->hasNext()) {
        echo $paginator->next("Next");
    }

    // the 'last' page button
    echo $paginator->last("Last");

    echo "</div>";
} else {
    echo "No trans found.";
}
?>