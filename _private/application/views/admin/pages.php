        <?php

$tr = '';
$showPages = false;
if (count($pages) > 0) {
	$showPages = true;
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
				<th style="width: 200px; text-align: left;">Title</th>
				<th style="width: 200px; text-align: left;">Slug</th>
				<th style="width: 200px; text-align: left;">Template</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php 

	foreach ($pages as $page) {
		$tr = '';
		$tr = '<tr>
					<td>' . $page['title'] . '</td>
					<td>' . $page['slug'] . '</td>
					<td>' . $page['template'] . '</td>
					<td><a href="/admin/pages/edit/' . $page['id'] . '">[edit]</a></td>
					<td><a href="/admin/pages/delete/' . $page['id'] . '">&nbsp;[X]</a></td>
				</tr>';
		echo $tr;
	}
			 ?>	
		</tbody>
	</table>
<?php } ?>
</section>
