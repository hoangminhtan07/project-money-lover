<h2> Users display</h2>
<?php if(empty($users)): ?>
	No User to display
<?php else: ?>
<table>
	<tr>
		<th> Id </th>
		<th> Name</th>
		<th> Created </th>
	</tr>
<?php foreach ($users as $user): ?>
	<tr>
		<td><?php echo $user['User']['id'] ?></td>
		<td><?php echo $user['User']['name'] ?></td>
		<td><?php echo $user['User']['created'] ?></td>
	</tr>
<?php endforeach; ?>
<?php endif; ?>
        <span>Login:<?php echo $this->Session->read("Username");?> <a href="logout">Logout</a></span>>