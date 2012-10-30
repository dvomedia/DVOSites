<h1>List of pages</h1>
<?php

$tr = '';
if (count($pages) > 0) {
	foreach ($pages as $page) {
		$tr .= '<tr>
					<td>' . $page['title'] . '</td>
					<td>' . $page['slug'] . '</td>
					<td>' . $page['template'] . '</td>
				</tr>';
	}
}

?>

<table>
	<thead>
		<tr>
			<th style="width: 200px">Title</th>
			<th style="width: 200px">Slug</th>
			<th style="width: 200px">Template</th>
		</tr>
	</thead>
	<tbody>
		<?php echo $tr; ?>	
	</tbody>
</table>
