<h2 style="font-family: greatVibes;"><?php echo $heading; ?></h2>
<?php echo $content; ?>
<?php if (true === is_array($items)) {
	foreach ($items as $item): ?>
		<article>
			<a href="<?php echo $item['slug']; ?>"><h3><?php echo $item['title'];?></h3></a>
			<div><?php echo $item['content'];?></div>
		</article>

	<?php endforeach;
}?>
