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

$photolist = '';
if (false === empty($photos)) {
	foreach ($photos as $photo) {
		$photolist .= '<img style="height: 100px;" src="http://1cb52187dec0664c2cf2-7993723f87d60393e0a153fc043f408f.r3.cf3.rackcdn.com/' . $photo['url'] . '"/>';
	}
}
?>

<section id="global">
	<?php echo $save_message; ?>
	<div class="page-header">
		<?php if (true === empty($album_id)) : ?>
			<h1>Create new Album</h1>	
		<?php else:?>
			<h1>Edit Album: <?php echo $title; ?></h1>
		<?php endif; ?>
		
	</div>
	<?php if (true === false): ?>
		<h4>Users that have access:</h4>
		<span>&nbsp;&gt;&nbsp;None [add]</span>
		<br /><br />
	<?php endif; ?>
	<form method="post" enctype="multipart/form-data">
		<input type="hidden" name="album[id]" value="<?php echo $album_id;?>" />
		<div class="row-fluid">
			Title: <input type="text" name="album[name]" value="<?php echo $title;?>">
		</div>
		<div class="row-fluid">
			<?php echo $photolist; ?>
		</div>
		<?php if (true === isset($album_id)): ?>
		<div class="row-fluid">
			Photos: <input name="fileuploads[]" type="file" multiple="" />
		</div>
		<?php endif; ?>

		<input class="input--submit" type="submit" value="Save" />
	</form>

</section>