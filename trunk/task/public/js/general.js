function abrir_cargando() {
	$(
		'<div id="loading" class="progress">' +
			'<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">' +
				'&nbsp;' +
			'</div>' + 
		'</div>'
	).dialog({
		modal:true
	});
	$('#loading').find('.progress-bar').css('width', '50%');
	$('#loading').find('.progress-bar').css('width', '100%');
}

function cerrar_cargando() {
	$('#loading').remove();
}

$( window ).resize(function() {
	resize();
});

function resize() {
	var height = $(window).height();	  
	$('body').css('height', height);	
	$('.container').css('height', height);
}

$(document).ready(function() {
	$('#sidebar a').tooltip({
		tooltipClass : 'container-tooltip'
	});
	resize();
});