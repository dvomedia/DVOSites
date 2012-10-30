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

<section id="global">
	<div class="page-header">
		<h1>List of Pages</h1>
	</div>

	

	<table>
		<thead>
			<tr>
				<th style="width: 200px; text-align: left;">Title</th>
				<th style="width: 200px; text-align: left;">Slug</th>
				<th style="width: 200px; text-align: left;">Template</th>
			</tr>
		</thead>
		<tbody>
			<?php echo $tr; ?>	
		</tbody>
	</table>

</section>



