var ultimo_selected_task = null;
var asign_user = '+';
var prevent_close_detail = false;
var project_selected_id;
var status_selected_id = 1;
(function($) {
	'use strict';


$(document).ready(function(e) {
	var initial_sort;
	if (typeof location.search === 'undefined') {
		alert('Tu navegador no es soportado');
	}
	
	// AUTO CLICK SELECT PROJECT
	if (location.search === '') {
		
		if (location.hash) {
			$('.projects_list').find('[href="' + location.hash + '"]').click();
		} else {
			$('.project:first').click();
		}
	}

	$('.tasks_list').sortable({
		axis: 'y',
		over: function( event, ui ) {
			initial_sort = $(ui.item).index()+1;
			$(ui.item).addClass('drag');
		},
		out: function( event, ui ) {
			$(ui.item).removeClass('drag');
		},
		update: function( event, ui ) {
			abrir_cargando();
			var new_sort = $(ui.item).index() + 1;
			var first_element,
				last_element,
				elements,
				i,
				current,
				change_element = new Array;

			if (new_sort < initial_sort) {

				first_element = $(ui.item);

				first_element.attr('sort', '');
				current = first_element;
				for (i = 0; i < (initial_sort - new_sort); i++) {
					current = $(current).next();

					change_element.push({
						id : current.attr('value'),
						sort : new_sort + 1 + i
					});

					$(current).attr('sort', new_sort + 1 + i);

				}

				change_element.push({
					id : first_element.attr('value'),
					sort : new_sort
				});

				first_element.attr('sort', new_sort);
			} else {

				first_element = $(ui.item);

				first_element.attr('sort', '');
				current = first_element;
				for (i = 0; i < (new_sort - initial_sort); i++) {

					current = $(current).prev();
					change_element.push({
						id : current.attr('value'),
						sort : new_sort - 1 - i
					});

					$(current).attr('sort', new_sort - 1 - i);

				}

				change_element.push({
					id : first_element.attr('value'),
					sort : new_sort
				});
				first_element.attr('sort', new_sort);

			}
			
			$.post(
				base_url + '/task/resort', 
				{
					tasks : change_element,
				}
			).complete(function(response, status) {
				cerrar_cargando();
				if (status === 'success' && response.responseText) {
					$(response.responseText).dialog({
						modal : true
					});
					$('#div_form_project .name_project').focus();
				}
			});
		}
	});

	autocomplete_user(".new_task");
});

// CREAR DIALOG OF NEW PROJECT
$(document).on('click', '#create_project', function(e) {
	e.preventDefault();
	abrir_cargando();
	
	$.post('project/create').complete(function(response, status) {
		cerrar_cargando();
		if (status === 'success' && response.responseText) {
			$(response.responseText).dialog({
				modal : true,
				close: function(e, ui) {
					prevent_close_detail = true;
					console.log('cerrando dialog');
				}
			});
		}
	});
});

// SUBMIT NEW PROJECT
$(document).on(
	'submit',
	'#form_create_project',
	function(e) {
		e.preventDefault();
		var name = $(this).find('[name="name"]').val();
		$.post('project/add', {
			'name' : name
		}).complete(function(response, status) {
			if (status === 'success') {
				var ret = $.parseJSON(response.responseText);

				if (ret.response === true) {
					$('#div_form_project').dialog('close');
					$('#div_form_project').dialog('destroy');
					$('.projects_list').append(
						'<li>' + 
							'<a href="#' + name + '" class="project" value="' + ret.id + '">' + 
								name +
							'</a>&nbsp;' +
							'<span class="badge closed">0</span>' +
							'<a href="#" class="edit_project" value="' + ret.id + '">' +
								'<span class="right glyphicon glyphicon-edit"></span>' +
							'</a>' +
						'</li>'
					);
					project_selected_id = ret.id;
					$('.project[value="' + ret.id + '"]').click();
				}
			}
		});
});
// SUBMIT NEW TASK
$(document).on('submit', '.create_task_form', function(e) {
	var parent_id,
		input_name = $(this).find('[name="title"]'),
		title = $(input_name).val();

	e.preventDefault();
	create_new_task(input_name, title, parent_id);
});

// SUBMIT NEW TASK
$(document).on('submit', '.create_subtask_form', function(e) {
	var parent_id = $(this).closest('div.subtask').attr('id'),
		input_name = $(this).find('[name="subtask_title"]'),
		title = $(input_name).val();

	e.preventDefault();
	create_new_task(input_name, title, parent_id);
});

// TODO: tengo que usar este codigo para poder crear subtareas
function create_new_task(input_name, title, parent_id) {
	var	matches = title.match(/\+\w*[^\+]/mg),
		users = [];
		
	abrir_cargando();
	if (matches) {
		for ( var i = 0; i < matches.length; i++) {
			users[i] = matches[i].replace(asign_user, '').trim();
			title = title.replace(matches[i],'');
		}
	}
	title = title.trim();
	
	$.post(base_url + '/task/add', {
		'title' : title,
		'users' : users,
		'project_id' : project_selected_id,
		'parent_id' : parent_id
	}).complete(function(response, status) {
		var ret,
			badge_project,
			count_task_openned;

		cerrar_cargando();
		if (status === 'success') {
			ret = $.parseJSON(response.responseText);
			if (ret.response === true) {
				$(input_name).val('');

				var selector_to_append = '.tasks_list';
				if (parent_id) {
					var selector_to_append_parent = '.tasks_list [value=' + parent_id + ']';
					selector_to_append = selector_to_append_parent + ' div.subtask';
				}

				$(selector_to_append).append(
					task_in_list(
						ret.id, 
						title,
						ret.users, 
						ret.status,
						ret.created,
						ret.sort,
						parent_id
					)
				);

				if (parent_id) {
					$(selector_to_append_parent).height()
					var curHeight = $(selector_to_append_parent).height();

					$(selector_to_append_parent).css('height', 'auto');
					var autoHeight = $(selector_to_append_parent).height();

					$(selector_to_append_parent).height(curHeight).animate({
						left: "slide",
					    height: autoHeight,
					    duration: 2000
					});

					$(input_name).focus();
				} else {
					$('.new_task').focus();
				}

				badge_project = $('.project[value="' + project_selected_id + '"]').parent().find('span.badge');
				count_task_openned = badge_project.html();
				
				badge_project.html(parseInt(count_task_openned)+1);
				if (count_task_openned === "0") {
					badge_project.removeClass('closed');
					badge_project.addClass('openned');
				}
			}
		}
		add_tooltip();
	});
}
// CLICK SELECT PROJECT
$(document).on('click', '.project', function(e) {

	e.preventDefault();
	abrir_cargando();
	project_selected_id = $(this).attr('value');

	// cerrar el detalle de la tarea
	$('.detail_task').animate({
		left: "slide",
		width: "hide",
	});

	$('.project').closest('li').removeClass('active');
	$(this).closest('li').addClass('active');

	get_task_list($(this).html());
});

function task_in_list(id, title, users, status_id, created, sort, parent_id) {
	
	var class_status = '';
	var icon = '';
	switch(status_id) {
	default:
	case 1:
		status = 'Abierto';
		class_status = 'background_openned';
		icon = 'glyphicon-record';
		break;
	case 2:
		status = 'Empezado';
		class_status = 'background_started';
		icon = 'glyphicon-play-circle';
		break;
	case 3:
		status = 'Terminado';
		class_status = 'background_done';
		icon = 'glyphicon-ok-circle';
		break;
	}

	if (parent_id) {
		class_status += ' subtask';
	}

	var ret = '<li value="'+ id +'" class="' + class_status + '" sort="' + sort + '">' +
			'<a href="#" class="show_status glyphicon ' + icon +'" value="' + status + '" ></a> ' +
			'<span class="title">' + title + '</span><div class="task_users">';
	
	var date = new Date(created);
	var current_date = new Date(Date());
	
	var anio = '';
	if (date.getFullYear() != current_date.getFullYear()) {
		anio = ' del ' + date.getFullYear(); 
	}
	
	var mes = '';
	var dia = '';
	// entre la semana actual
	if (date > current_date.getWeek()[0] && current_date < date.getWeek()[1]) {	    
		dia = dias[date.getDay()];
	} else {
		dia = date.getDate();
		mes = ' de ' + meses[date.getMonth()];
	}
	
	ret +='<span class="left created">' + dia + mes + anio  +'</span>';

	for ( var i = 0; i < users.length; i++) {
		ret += '<a href="#" class="right user">' + users[i] + '</a>';
	}
	ret += '</div>';

	if (typeof parent_id == "undefined") {

		var input_new_task = $('<input>', {
			'type': 		'text',
			'name': 		'subtask_title',
			'placeholder': 	'Agregar nueva subtarea',
			'class': 		'new_subtask form-control ui-autocomplete-input'
		});

		var span_plus = $('<span>', {
			'class': 'glyphicon glyphicon-plus'
		});

		ret += 
		'<div class="subtask" id=' + id + '>' +
			'<div class="new_subtask_div">' +
				'<form class="create_subtask_form">' +
					$(input_new_task[0]).prop('outerHTML') +
					$(span_plus[0]).prop('outerHTML') +
				'</form>' +
			'</div>' +
		'</div>';
	}

	ret += '</li>';
	return ret;
}
// CLICK EDIT PROJECT
$(document).on('click', '.edit_project', function(e) {
	e.preventDefault();
	abrir_cargando();
	var id = $(this).attr('value');

	$.post('project/editform', {
		id : id
	}).complete(function(response, status) {
		cerrar_cargando();
		if (status === 'success' && response.responseText) {
			$(response.responseText).dialog({
				modal : true
			});
			$('#div_form_project .name_project').focus();
		}
	});
});

// SUBMIT EDIT PROJECT
$(document).on('submit', '#form_edit_project', function(e) {
	e.preventDefault();
	abrir_cargando();
	var name = $(this).find('input[name="name"]').val();
	var id = $(this).find('input[name="id"]').val();

	$.post('project/edit', {
		id : id,
		name : name
	}).complete(function(response, status) {
		cerrar_cargando();
		if (status === 'success') {
			var ret = $.parseJSON(response.responseText);
			if (ret) {
				$('#div_form_project').dialog('destroy');
				$('.project[value="' + id + '"]').html(name);
			}
		}
	});
});

// CLICK CANCEL EDIT PROJECT
$(document).on('click', '.cancel_edit_project', function(e) {
	e.preventDefault();
	$('#div_form_project').dialog('destroy');
	return false;
});

//CLICK CHANGE STATUS
$(document).on('click', '.change_status', function(e) {
	e.preventDefault();
	abrir_cargando();
	var task_id = $(this).attr('value');
	var descripcion = $(this).attr('id');
	
	$.post(base_url + '/task/changestatus', {
		id : task_id,
		status: descripcion
	}).complete(function(response, status) {
		cerrar_cargando();
		if (status === 'success') {
			var ret = $.parseJSON(response.responseText);
			if (ret.response) {
				
				var li = change_status_task(task_id, descripcion);
				
				if (status_selected_id) { 
					li.hide(500);
				}

				if (typeof websocket !== 'undefined' && websocket.readyState === websocket.OPEN) {
				    
					var count_task_openned = $('.project[value="' + project_selected_id + '"]')
						.parent()
						.find('span.badge')
						.html();
					
				    var msg = {
				        type: 				'change_status',
				        message: 			li.find('.title').html(),
				        name: 				current_user_id,
				        count_task_openned: count_task_openned,
				        project_id: 		project_selected_id,
				        status:				descripcion,
				        user_name:			current_user_name,
				        task_id:			task_id
				    };
				    
				    //convert and send data to server
				    websocket.send(JSON.stringify(msg));
				}
				
			} else {
				alert('No se pudo cambiar el estado');
			}
		}
	});
});

function add_tooltip() {
	
	$('.show_status').click(function(e) {
		e.preventDefault();
	});
	
	$('.show_status').tooltipster({
		contentAsHTML: true,
		trigger:'click',
		theme: 'tooltipster-shadow',
		delay: 0,
		animation: false,
		speed: 0,
		functionBefore: function(origin, continueTooltip) {
			continueTooltip();
			var task_id = $(origin).closest('li').attr('value');
			var html_tooltip = 
				'<div class="tooltip_status"><ul class="list-unstyled">';
			switch($(origin).attr('value')) {
				case 'Abierto': 
					html_tooltip += 
						'<li>' + 
							'<span class="glyphicon glyphicon-play-circle"></span>' + 
							'<a href="#" class="change_status" id="Empezado" status_id="' + 2 + '" value="'+task_id+'">Empezada</a>' +
						'</li>' +
						'<li>' +
							'<span class="glyphicon glyphicon-ok-circle"></span>' +
							'<a href="#" class="change_status" id="Terminado" status_id="' + 3 + '" value="'+task_id+'">Hecha</a>' +
						'</li>';
					break;
				case 'Terminado':
					html_tooltip += 
						'<li>' +
							'<span class="glyphicon glyphicon-record"></span>' +
							'<a href="#" class="change_status" id="Abierto" status_id="' + 1 + '" value="'+task_id+'">Abierta</a>' +
						'</li>' +
						'<li>' + 
							'<span class="glyphicon glyphicon-play-circle"></span>' +
							'<a href="#" class="change_status" id="Empezado" status_id="' + 2 + '"  value="'+task_id+'">Empezada</a>' + 
						'</li>';
					break;
				case 'Empezado':
					html_tooltip += 
						'<li>' + 
							'<span class="glyphicon glyphicon-record"></span>' + 
							'<a href="#" class="change_status" id="Abierto" status_id="' + 1 + '" value="'+task_id+'">Abierta</a>' + 
						'</li>' +
						'<li>' + 
							'<span class="glyphicon glyphicon-ok-circle"></span>' + 
							'<a href="#" class="change_status" id="Terminado" status_id="' + 3 + '" value="'+task_id+'">Hecha</a>' + 
						'</li>';
					break;
			}
				
			html_tooltip += '</ul></div>';
			origin.tooltipster('content', html_tooltip);
		},
		content: ''
	});
}

// SHOW DETAIL TASK
$(document).on('click', 'li.subtask .title', function(e) {
	var li = $(this).closest('li');
	var id = li.attr('value');
	
	$('.tasks_list li').removeClass('selected');
	li.addClass('selected');
	
	if (ultimo_selected_task != null && ultimo_selected_task != id) {
		ultimo_selected_task = id;
		
		show_task_detail(id, li);
		
		$('.detail_task').animate({
			left: "slide",
		    width: "show",
		}, function() {
		});
		return false;
	}
	
	ultimo_selected_task = id;
	show_task_detail(id,li);
	
	$('.detail_task').animate({
		left: "slide",
	    width: "toggle"
	}, function() {
		if (!$('.detail_task').is(':visible')) {
			$('.tasks_list li').removeClass('selected');
		}
	});
});

$(document).on('click', '.tasks_list li .title', function(e) {
	var li = $(this).closest('li');
	var id = li.attr('value');
	
	$('.tasks_list li').removeClass('selected');
	li.addClass('selected');
	
	get_subtasks(id);
});

function show_task_detail(id, li) {
    $('.detail_task').html('<div class="cargando"><img src="' + base_url + '/public/img/cargando.gif"></div>');
	
    
	$.post(
		base_url + '/task/view', 
		{
			id : id
		}
	).done(function(response) {
        $('.detail_task').html(response);	        
		if ($('.detail_task').is(':visible') && 
			$('.detail_task').find('.remove').attr('value') === id) {
			
			$('.tasks_list li').removeClass('selected');
			li.addClass('selected');
			
		}
		
		$('.autosize').autosize({ 
			callback: function() {
				$(this).height($(this).height());
			}
		});
	}).fail(function() {
	    alert( "No se puede cargar" );
	});
}

$(document).on('focus', '.search_task', function() {
	console.log('focus');
	
	$(this).animate({width: "320px"});
	$('.search_form .glyphicon').animate({left: "298px"});
});
$(document).on('blur', '.search_task', function() {
	console.log('blur');
	
	$(this).animate({width: "200px"});
	$('.search_form .glyphicon').animate({left: "178px"});
});
// Remove / delete project
$(document).on('click', '#form_edit_project .remove',function(e){
	e.preventDefault();
	var id = $(this).attr('value');
	
	if (confirm('¿Está seguro que desea borrar el projecto?')) {		
		$.post(
				base_url + '/project/remove', 
				{
					id : id
				},
				'json'
		).done(function(response) {
			
			if (response) {
				$('.projects_list .project[value="' + id + '"]').closest('li').remove();
				$('.project:first').click();
				$('#div_form_project').dialog('destroy');
			} else {
				alert( "No se puede borrar" );
			}
		}).fail(function() {
			alert( "No se puede borrar" );
		});
	}
});

// CLICK REMOVE TASK
$(document).on('click', '#view_task .remove', function(e) {
	e.preventDefault();
	var id = $(this).attr('value');
	if (confirm('¿Está seguro que desea borrar la tarea?')) {		
		$.post(
				base_url + '/task/remove', 
				{
					id : id
				},
				'json'
		).done(function(response) {
			
			if (response) {
				var status = $('.tasks_list li[value="'+ id + '"]').find('.show_status').attr('value');
				
				switch (status) {
				case 'Abierto':
					var badge_project = $('.project[value="' + project_selected_id + '"]').parent().find('span.badge');
					var count_task_openned = badge_project.html();
					
					if (count_task_openned != '0') {
						badge_project.html(parseInt(count_task_openned)-1);
						
						if (count_task_openned === '1') {
							badge_project.removeClass('openned');
							badge_project.addClass('closed');
						}
					}
					break;
				}
				$('.tasks_list li[value="'+ id + '"]').remove();
				$('.detail_task').animate({
					left: "slide",
				    width: "hide",
				});
				
			} else {
				alert( "No se puede borrar" );
			}
		}).fail(function() {
			alert( "No se puede borrar" );
		});
	}
});

var tmp_selected_task = ultimo_selected_task;

$(document).on('focus', '.title_view_form', function(e) {
	tmp_selected_task = ultimo_selected_task;
});

//SUBMIT EDIT TITLE'S TASK
$(document).on('keypress', '.title_view_form', function(e) {
	
	if (e.keyCode === 13) {
		e.preventDefault();
		var input = $(this).find('[name="title"]');

		save_detail_field(input, 'title', input.val());
	}
});

//SUBMIT Date'S TASK
$(document).on('keypress', '.date_end', function(e) {
	
	if (e.keyCode === 13) {
		e.preventDefault();
		var input = $(this);

		save_detail_field(input, 'end', input.val());
	}
});

//SUBMIT TIME'S TASK
$(document).on('keypress', '.time_end', function(e) {
	
	if (e.keyCode === 13) {
		e.preventDefault();
		var input = $(this);

		save_detail_field(input, 'time', input.val());
	}
});

function save_detail_field(input, key, value) {
	if (value == '') {
		input.css('background-color', '#FF5E5E');
		input.animate({backgroundColor: "#fff"}, 1000);
		return;
	}

	var values = {
		id: ultimo_selected_task
	};

	values[key] = value;

	$.post(
		base_url + '/task/edit', 
		values,
		'json'
	).done(function(response) {
		if (response) {
			if (key == 'title') {
				$('.tasks_list li[value="'+ tmp_selected_task + '"] .title').html(value);
			}

			input.css('background-color', '#A0E0BC');
			input.animate({backgroundColor: "#fff"}, 1000);
		} else {
			input.css('background-color', '#FF5E5E');
			input.animate({backgroundColor: "#fff"}, 1000);
		}
	}).fail(function() {
		input.css('background-color', '#FF5E5E');
		input.animate({backgroundColor: "#fff"}, 1000);
	});
}

//SUBMIT EDIT TASK
$(document).on('keypress', '.description_view_form', function(e) {
	if (e.keyCode === 13 && e.shiftKey === false) {
		e.preventDefault();
		var input = $(this).find('[name="description"]');
		var description = input.val();
		$.post(
			base_url + '/task/edit',
			{
				id : ultimo_selected_task,
				description : description
			},
			'json'
		).done(function(response) {
			if (response) {
				input.css('background-color', '#A0E0BC');
				input.animate({backgroundColor: '#fff'}, 1000);
			} else {
				input.css('background-color', '#FF5E5E');
				input.animate({backgroundColor: '#fff'}, 1000);
			}
		}).fail(function() {
			input.css('background-color', '#FF5E5E');
			input.animate({backgroundColor: '#fff'}, 1000);
		});
	}
});

$(document).keyup(function(e){
	if (prevent_close_detail) {
		prevent_close_detail = false;
		return;
	}
	// SCAPE CODE
	if(e.keyCode === 27) {
		if ($('.detail_task #view_task').length !== 0) {
			$('.detail_task').animate({
				left: 'slide',
			    width: 'toggle',
			});
		}
	}
});

//CLICK SELECT STATUS
$(document).on('click', '.search_status', function(e) {
	e.preventDefault();
	abrir_cargando();

	$('.tasks_list').html('');
	// cerrar el detalle de la tarea
	$('.detail_task').animate({
		left: 'slide',
	    width: 'hide'
	});
	status_selected_id = $(this).attr('id');
	
	get_task_list();
});

// CLICK USER
$(document).on('click', '.users_list .user', function(e) {
	e.preventDefault();
	abrir_cargando();
	
	var user_id = $(this).attr('value');
	var user = $(this).html();
	get_task_list('', user_id);
});

//SEARCH TASK
$(document).on('submit', '.search_form', function(e) {
	var title;
	e.preventDefault();
	abrir_cargando();
	
	title = $(this).find('.search_task').val();
	$.post(base_url + '/task/search', {
		'title': title,
		'project_id' : project_selected_id 
	}).complete(function(response, status) {
		var ret,
			i;
		cerrar_cargando();
		$('.tasks_list').html('');
		if (status === 'success') {
			ret = $.parseJSON(response.responseText);
			if (ret.response === true) {
				for (i = 0; i < ret.tasks.length; i++) {
					$('.tasks_list').append(
						task_in_list(ret.tasks[i].id,
							ret.tasks[i].title,
							ret.tasks[i].users,
							ret.tasks[i].status,
							ret.tasks[i].created,
							ret.tasks[i].sort
						)
					);
				}
			}
		}
		
		$('.detail_task').html('');
		add_tooltip();
	});
});

//CREAR DIALOG OF NEW PROJECT
$(document).on('click', '#create_user', function(e) {
	e.preventDefault();
	abrir_cargando();
	
	$.post('usuario/create').complete(function(response, status) {
		cerrar_cargando();
		if (status === 'success' && response.responseText) {
			$(response.responseText).dialog({
				modal : true,
				close: function(e, ui) {
					prevent_close_detail = true;
				}
			});
		}
	});
});

//SUBMIT NEW USER
$(document).on(
	'submit',
	'#form_create_usuario',
	function(e) {
		e.preventDefault();
		var name = $(this).find('[name="name"]').val();
		$.post('usuario/add', {
			'name' : name,
			'password' : $(this).find('[name="password"]').val(),
			'repeat_password' : $(this).find('[name="repeat_password"]').val(),
			'profile_id' : $(this).find('[name="profile_id"]').val(),
			'mail' : $(this).find('[name="mail"]').val()
		}).complete(
			function(response, status) {
				if (status === 'success') {
					var ret = $.parseJSON(response.responseText);

					if (ret.response === true) {
						$('#div_form_project').dialog('close');
						$('#div_form_project').dialog('destroy');
						$('.users_list').append(
								'<li>' + 
									'<a href="#' + name + '" class="user" value="' + ret.id + '">' + 
										name +
									'</a>&nbsp;' +
									'<span class="badge openned">Nunca</span>' +
									'<a href="#" class="edit_user" value="' + ret.id + '">' +
										'<span class="right glyphicon glyphicon-edit"></span>' +
									'</a>' +
								'</li>');
						project_selected_id = ret.id;
						$('.project[value="' + ret.id + '"]').click();
					} else {
						alert(ret.error);
					}
				}
			}
		);
});

function get_task_list(title_project, user_id) {
	var task_to_sort;

	$.post(base_url + '/task/list', {
		'project_id' : project_selected_id,
		'status_id' : status_selected_id,
		'user_id': user_id
	}).complete(function(response, status) {

	    if ($('#loguear',jQuery.parseHTML(response.responseText)).length > 0) {
	        window.location = 'usuario/loguear';
	        return false;
	    }
	    
		cerrar_cargando();
		$('.tasks_list').html('');
		if (status === 'success') {
			var ret = $.parseJSON(response.responseText);
			if (ret.response === true) {

				for ( var i = 0; i < ret.tasks.length; i++) {
					$('.tasks_list').append(
						task_in_list(
							ret.tasks[i].id,
							ret.tasks[i].title,
							ret.tasks[i].users,
							ret.tasks[i].status, 
							ret.tasks[i].created,
							ret.tasks[i].sort
						)
					);
				}

				task_to_sort = $('.tasks_list').children('li');
				task_to_sort.sort(
					function(a,b) {
						var an = parseInt(a.getAttribute('sort')),
							bn = parseInt(b.getAttribute('sort'));
						if (an > bn) {
							return 1;
						}
						if (an < bn) {
							return -1;
						}
						return 0;
					}
				);
				task_to_sort.detach().appendTo($('.tasks_list'));

				$('.search_status').removeClass('active');
				if (ret.status_id === null) {
				    // ver como dejar active el ALL 
				    console.log('busco por todos');
				} else {
				    $('.search_status[id=' + ret.status_id + ']').addClass('active');
				    
				}

				$('.users_list .user').removeClass('active');
				if (ret.user_id) {
				    $('.users_list [value=' + ret.user_id + ']').addClass('active');
				}

			}
			location.hash = title_project;
			
			$(".search_status").removeClass('active');
			$(".search_status[id=" + ret.status_id + "]").addClass('active');
		}
		
		$('.detail_task').html('');
		add_tooltip();
	});
}

function get_subtasks(parent_id) {
	$.post(base_url + '/task/list', {
		'parent_id' : parent_id
	}).complete(function(response, status) {
	    if ($('#loguear',jQuery.parseHTML(response.responseText)).length > 0) {
	        window.location = 'usuario/loguear';
	        return false;
	    }

		var selector_li_parent = '.tasks_list [value=' + parent_id + ']';
	    cerrar_cargando();
	    $(selector_li_parent + ' li.subtask').remove();
		if (status === 'success') {
			var ret = $.parseJSON(response.responseText);
			if (ret.response === true) {

				for ( var i = 0; i < ret.tasks.length; i++) {
					$(selector_li_parent  + ' div.subtask').append(
						task_in_list(
							ret.tasks[i].id,
							ret.tasks[i].title,
							ret.tasks[i].users,
							ret.tasks[i].status, 
							ret.tasks[i].created,
							ret.tasks[i].sort,
							parent_id
						)
					);
				}
			}
			if ($(selector_li_parent).height() <= 30) {

				var curHeight = $(selector_li_parent).height();
				$(selector_li_parent + ' .subtask').show();

				$(selector_li_parent).css('height', 'auto');
				var autoHeight = $(selector_li_parent).height();

				$(selector_li_parent).height(curHeight).animate({
					left: "slide",
				    height: autoHeight
				});
			} else {

				$(selector_li_parent).animate({
					left: "slide",
				    height: "30px"
				}, function() {
					$(selector_li_parent + ' .subtask').hide();
				});
			}

			autocomplete_user("input[name='subtask_title']");
		}
	});
}

function autocomplete_user(selector) {
		$(selector).autocomplete({
		source : function(request, response) {
			// Si puso '+user_name' o '+'
			if (
				request.term.match(/\+\w*[^\+]/mg) ||
				$(selector).val()[$(selector).val().length-1] === asign_user
			) {
								
				var matches = request.term.match(/\+\w*[^\+]/mg);
				
				var discard = [];
				if (matches) {
					for ( var i = 0; i < matches.length; i++) {
						if (i === (matches.length-1)) {
							// Si es '+' es porque el ultimo match es discard
							if ($(selector).val()[$(selector).val().length-1] === asign_user) {
								discard[i] = matches[i].replace(asign_user,'').trim();
							}
							request.term = matches[i].replace(asign_user,'');						
						} else {
							discard[i] = matches[i].replace(asign_user,'').trim();						
						}
					}
				}
					
				//Si puso '+'
				if ($(selector).val()[$(selector).val().length-1] === asign_user) {
					request.term = '';
				}
				
				
				$.ajax({
					url : base_url + '/usuario/search',
					data : {
						name : request.term,
						discard: discard
					},
					dataType : 'json',
					type : 'GET',
					success : function(data) {
						var ultimos_buscados = data;
						response($.map(data, function(item) {
							return {
								label : item.name,
								value : item.id
							};
						}));
					},
					error : function(request, status, error) {
						alert(error);
					}
				});
			} else {
				$(selector).autocomplete('close');
			}
			
		},
		minLength : 1,
		select : function(event, ui) {
			console.log('select');
			var value = $(selector).val();
			var regex = new RegExp('\\+[\\w]*$');
			
			$(selector).val(value.replace(regex, asign_user + ui.item.label));
			return false;
		},
		focus : function(event, ui) {
			console.log('focus');
			var value = $(selector).val();
			var regex = new RegExp('\\+[\\w]*$');
			
			$(selector).val(value.replace(regex, asign_user + ui.item.label));					
			
			return false;
		},
		response : function(event, ui) {
		}
	});
}
})(jQuery);