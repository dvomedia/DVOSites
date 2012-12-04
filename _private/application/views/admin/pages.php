        <?php

$tr = '';
$showPages = false;
if (count($pages) > 0) {
	$showPages = true;
	foreach ($pages as $page) {
		$tr .= '<tr>
					<td><a href="/admin/pages/edit/' . $page['id'] . '">[edit]</a></td>
					<td>' . $page['title'] . '</td>
					<td>' . $page['slug'] . '</td>
					<td>' . $page['template'] . '</td>
				</tr>';
	}
}

?>

<section id="global">
	<div class="page-header">
		<h1>List of Pages</h1>
	</div>

	<?php

	if (true === $showPages) { ?>

	<table>
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th style="width: 200px; text-align: left;">Title</th>
				<th style="width: 200px; text-align: left;">Slug</th>
				<th style="width: 200px; text-align: left;">Template</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $tr; ?>	
		</tbody>
	</table>
	<?php } ?>

</section>



