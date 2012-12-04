<?php
$save_message = '';
if (true === $post) {
	if (true === $success) {
		$save_message .= '<div class="info success">';
		$save_message .= 'WOOOP WOOOP';
	} else {
		$save_message .= '<div class="info fail">';
		$save_message .= 'FAIL WAIL.';
	}

	$save_message .= '</div>';
}

$albumlist = '';
if (true === isset($albums) && true === is_array($albums)) {
	$albumlist = '<table><tr><td>Album</td><td>Access</td></tr>';
	foreach ($albums as $album) {
		if (true === in_array($album['id'], $user_albums)) {
			$albumlist .= '<tr><td>' . $album['title'] . '</td><td><input name="user[albums][]" value="' . $album['id'] . '" type="checkbox" checked="checked"/></td></tr>';	
		} else {
			$albumlist .= '<tr><td>' . $album['title'] . '</td><td><input name="user[albums][]" value="' . $album['id']. '" type="checkbox" /></td></tr>';
		}
		
	}
	$albumlist .= '</table>';	
}


?>

<section id="global">
	<?php echo $save_message; ?>
	<div class="page-header">
		<?php if (true === empty($user_id)) : ?>
			<h1>Create new User</h1>	
		<?php else:?>
			<h1>Edit User: <?php echo $username; ?></h1>
		<?php endif; ?>
		
	</div>
	<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="user[id]" value="<?php echo $user_id;?>" />
		<div class="row-fluid">
			Username: <input type="text" name="user[username]" value="<?php echo $username;?>">
		</div>
		<?php if (true === empty($user_id)) : ?>
		<div class="row-fluid">
			Password: <input type="text" readonly="readonly" value="Will be generated">
		</div>
		<?php endif; ?>
		<div class="row-fluid">
			<?php echo $albumlist; ?>
		</div>

		<input class="input--submit" type="submit" value="Save" />
	</form>

</section>