<h1><?php echo $heading; ?></h1>
<?php echo $content; ?>
<br /><br />
<?php

if (count($items) > 0) {
	foreach ($items as $photo) {
		print $photo['caption'] . '&nbsp;<a href="' . $photo['url'] . '" target="_blank">View photo</a><br />';
	}
}