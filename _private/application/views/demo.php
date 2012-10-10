<?php 
/* shouldn't really load javascript here, but for the sake of brevity.. */
?>
<script type="text/javascript">
	$(document).ready(function(){
		var items = [];
		items.push('<thead><tr>');
		items.push('<th>Heading1</th>');
		items.push('<th>Heading2</th>');
		items.push('<th>Heading3</th>');
		items.push('</tr></thead>');
		$('<table/>', {
		    'class': 'results-table',
		    'id' : 'resultstable',
		    'width' : '100%',
		    'cellpadding' : '0',
		    'cellspacing' : '0',
		    html: items.join('')
		}).appendTo('#results');

		$('#save_person').click(function(){
			$.ajax({
				type: 'PUT',
				url: "/api/person/1",
				dataType: "jsonp",
				data: {
					id: 1,
				},
				success: function( data ) {
					alert(data);
				}
			});
		});
	});
</script>

<div id="results"><!-- --></div>

<a id="save_person" href="#">Save</a>