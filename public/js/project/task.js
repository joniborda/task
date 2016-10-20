$('.save').click(function(e) {
	var tr = $(this).closest('tr'),
		active = tr.find('input[name="active"]').is(':checked'),
		date_start = tr.find('input[name="date_start"]').val(),
		date_end = tr.find('input[name="date_end"]').val(),
		id = $(this).attr('id');

	$.post(
		base_url + '/project/savegantt',
		{
			active : active,
			date_start : date_start,
			date_end : date_end,
			id : id,
		}
	).complete(function(response, status) {
		cerrar_cargando();
		if (status === 'success' && response.responseText) {
			$(response.responseText).dialog({
				modal : true
			});
			
		}
	});
});