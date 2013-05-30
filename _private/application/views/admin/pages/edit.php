<?php
$save_message = '';
if (true === $post) {
	if (true === $success) {
		$save_message .= '<div class="info success">';
		$save_message .= 'Changes saved'; // WOOOP WOOOP
	} else {
		$save_message .= '<div class="info fail">';
		$save_message .= 'Errors saving changes'; // FAIL WAIL
	}
	$save_message .= '</div>';
}
?>
<section id="global">
	<?php echo $save_message; ?>
	<div class="page-header">
		<h1>Edit Page: <?php echo $title; ?></h1>
	</div>
	<form method="post">
		<input type="hidden" name="page[id]" value="<?php echo $page_id;?>" />
		<input type="hidden" name="page[slug]" value="<?php echo $slug;?>" />
		<div class="row-fluid">
			<textarea name="page[content]" style="height: 200px; width: 100%; padding: 0"><?php echo $content; ?></textarea>
		</div>
		<input class="input--submit" type="submit" value="Save" />
	</form>
</section>