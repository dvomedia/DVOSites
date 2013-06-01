<h1><?php echo $heading; ?></h1>
<?php echo $content; ?>
<?php if (true === is_array($items)) {
	foreach ($items as $item) { 
/*
Array
(
    [0] => id
    [1] => title
    [2] => template
    [3] => content
    [4] => md_content
    [5] => md_render
    [6] => slug
    [7] => protected
    [8] => active
    [9] => category_title
    [10] => special
    [11] => site_title
    [12] => site_url
    [13] => skin
)*/

		?>
		<article>
			<a href="<?php echo $item['slug']; ?>"><h3><?php echo $item['title'];?></h3></a>
			<div><?php 
				// echo $item['category_title'];

				if ($slug = 'md') {
					$snippet = substr($item['content'],0,140) . '... <a href="'. $item['slug']. '">read more</a><br /><br />' ;
					echo $snippet;
				} else {
					echo $item['content'];
				}
			?></div>
		</article>

	<?php } // end foreach
}?>