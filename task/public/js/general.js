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
	/*
	$('.autosize').autosize({ 
		callback: function() { 
			$(this).height($(this).height() - 20);
		}
	});*/
});

Date.prototype.getWeek = function(start)
{
        //Calcing the starting point
    start = start || 0;
    var today = new Date(Date(this.setHours(0, 0, 0, 0)));
    var day = today.getDay() - start;
    var date = today.getDate() - day;

        // Grabbing Start/End Dates
    var StartDate = new Date(Date(today.setDate(date)));
    var EndDate = new Date(Date(today.setDate(date + 6)));
    return [StartDate, EndDate];
}

var meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct','Nov', 'Dic'];
var dias = ['Dom', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Lun'];