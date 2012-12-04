        <?php

$tr = '';
$showAlbums = false;
if (count($albums) > 0) {
	$showAlbums = true;
	foreach ($albums as $album) {
		$tr .= '<tr>
					<td><a href="/admin/albums/edit/' . $album['id'] . '">[edit]</a></td>
					<td>' . $album['title'] . '</td>
				</tr>';
	}
}

?>

<section id="global">
	<div class="page-header">
		<h1>List of Albums</h1>
	</div>

	<?php if (true === $showAlbums) { ?>
		<p>Create a new one? <a href="/admin/albums/edit">[yes]</a></p>
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
		<p>No albums yet! Create one? <a href="/admin/albums/edit">[yes]</a></p>

	<?php }?>

</section>



