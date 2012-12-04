        <?php

$tr = '';
$showUsers = false;
if (count($users) > 0) {
	$showUsers = true;
	foreach ($users as $user) {
		$tr .= '<tr>
					<td><a href="/admin/users/edit/' . $user['id'] . '">[edit]</a></td>
					<td>' . $user['username'] . '</td>
				</tr>';
	}
}

?>

<section id="global">
	<div class="page-header">
		<h1>List of Users</h1>
	</div>

	<?php if (true === $showUsers) { ?>
		<p>Create a new one? <a href="/admin/users/edit">[yes]</a></p>
		<table>
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th style="width: 200px; text-align: left;">Title</th>
				</tr>
			</thead>
			<tbody>
				<?php echo $tr; ?>	
			</tbody>
		</table>
	<?php } else {
		?>
		<p>No users yet! Create one? <a href="/admin/users/edit">[yes]</a></p>

	<?php }?>

</section>