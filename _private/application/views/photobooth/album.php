<h1><?php echo $heading; ?></h1>
<?php echo $content; ?>
<br /><br />
<?php

if (count($items) > 0) {
	$div = '';
	foreach ($items as $photo) {
		$url = 'http://1cb52187dec0664c2cf2-7993723f87d60393e0a153fc043f408f.r3.cf3.rackcdn.com/' . $photo['url'];
		//print $photo['caption'] . '&nbsp;<a href="http://1cb52187dec0664c2cf2-7993723f87d60393e0a153fc043f408f.r3.cf3.rackcdn.com/' . $photo['url'] . '" target="_blank">View photo</a><br />';
		$div .= '<div style="height: 125px; width: 200px; padding: 10px; margin: 5px; border: 1px solid #333333; display: inline-table;">';
		$div .= '<img style="max-height: 100%;" src="' . $url . '" /><br/>';	
		$div .= '<div href="' . $url . '" class="fb-like" data-send="true" data-width="200" data-show-faces="false"></div>';
		$div .= '</div>';
	}
	
}

print $div;