
$(document).ready(function() {
'use strict';
	$('#sidebar a').tooltip({
		tooltipClass : 'container-tooltip'
	});
	resize();
	
	$('.title_my_projects').click(function(e) {
		e.preventDefault();
		$('.projects_list').toggle(300);
	});
});

function abrir_cargando() {
'use strict';
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
'use strict';
	$('#loading').remove();
}

$( window ).resize(function() {
'use strict';
	resize();
});

function resize() {
'use strict';
	var height = $(window).height();	  
	$('body').css('height', height);	
	$('.container').css('height', height);
}

Date.prototype.getWeek = function(start)
{
'use strict';
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

function notificar(objeto) {
'use strict';
    if (Notification.permission === 'granted') {
        
        if (typeof objeto.title === 'undefined') {
            objeto.title = '';
        }
        
        if (typeof objeto.message === 'undefined') {
            objeto.message = '';
        }
        
        if (typeof objeto.status !== 'undefined') {
            objeto.message += '[' + objeto.status + ']';
        }
        
        if (typeof objeto.user_name !== 'undefined') {
            objeto.message += ' - [' + objeto.user_name + ']';
        }
        
        var notification = new Notification(objeto.title, {
            icon: 'http://cdn.sstatic.net/stackexchange/img/logos/so/so-icon.png',
            body: objeto.message
        });
        
        if (typeof objeto.link !== 'undefined') { 
            notification.onclick = function () {
                window.open(objeto.link);
            };
        }
        
        setTimeout(function() {
            notification.close();
        }, 4000);
    }
}

function count_task_in_project(count_task_openned, project_selected_id) {
'use strict';

    var badge_project = $('.project[value="' + project_selected_id + '"]').parent().find('span.badge');
    
    if (count_task_openned < 0) {
    	count_task_openned = 0;
    }
    
    badge_project.html(parseInt(count_task_openned));
    if (count_task_openned !== '0') {
        badge_project.removeClass('closed');
        badge_project.addClass('openned');
    } else {
        badge_project.removeClass('openned');
        badge_project.addClass('closed');
    }
}

/**
 * change_status_task
 * 
 * @param task_id
 * @param status_name
 * 
 * @return li The li's input tag
 */
function change_status_task(task_id, status_name) {
'use strict';
	var li = $('.tasks_list').find('li[value="'+ task_id +'"]');
	
	var badge_project = $('.project[value="' + project_selected_id + '"]').parent().find('span.badge');
	var count_task_openned = badge_project.html();
	
	switch(status_name) {
		case 'Abierto':
			li.removeClass('background_done');
			li.find('a.show_status').removeClass('glyphicon-ok-circle');
			$('.detail_task .show_status').removeClass('glyphicon-ok-circle');
			li.removeClass('background_started');
			li.find('a.show_status').removeClass('glyphicon-play-circle');
			$('.detail_task .show_status').removeClass('glyphicon-play-circle');
			li.addClass('background_openned');
			li.find('a.show_status').addClass('glyphicon-record');
			$('.detail_task .show_status').addClass('glyphicon-record');
			
			count_task_openned = parseInt(count_task_openned)+1;
			
			count_task_in_project(count_task_openned, project_selected_id);
			break;
		case 'Terminado':
		    if (li.hasClass('background_openned')) {
		    	count_task_openned = parseInt(count_task_openned)-1;
		    	
		        count_task_in_project(count_task_openned, project_selected_id);
		    }
		    
			li.removeClass('background_openned');
			li.find('a.show_status').removeClass('glyphicon-record');
			$('.detail_task .show_status').removeClass('glyphicon-record');
			li.removeClass('background_started');
			li.find('a.show_status').removeClass('glyphicon-play-circle');
			$('.detail_task .show_status').removeClass('glyphicon-play-circle');
			li.addClass('background_done');
			li.find('a.show_status').addClass('glyphicon-ok-circle');
			$('.detail_task .show_status').addClass('glyphicon-ok-circle');
			break;
		case 'Empezado':
		    if (li.hasClass('background_openned')) {
		    	count_task_openned = parseInt(count_task_openned)-1;
		    	
                count_task_in_project(count_task_openned, project_selected_id);
            }
		    
			li.removeClass('background_openned');
			li.find('a.show_status').removeClass('glyphicon-record');
			$('.detail_task .show_status').removeClass('glyphicon-record');
			li.removeClass('background_done');
			li.find('a.show_status').removeClass('glyphicon-ok-circle');
			$('.detail_task .show_status').removeClass('glyphicon-ok-circle');
			li.addClass('background_started');
			li.find('a.show_status').addClass('glyphicon-play-circle');
			$('.detail_task .show_status').addClass('glyphicon-play-circle');
			
			break;
	}
	
	li.find('a.show_status').attr('value', status_name);
	
	return li;
}