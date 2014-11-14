var asign_user = '+';
var prevent_close_detail = false;
var project_selected_id = null;

// CREAR DIALOG OF NEW PROJECT
$(document).on('click', '#create_project', function(e) {
	e.preventDefault();
	abrir_cargando();
	
	$.post('project/create').complete(function(response, status) {
		cerrar_cargando();
		if (status == 'success' && response.responseText) {
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
			}).complete(
					function(response, status) {
						if (status == 'success') {
							var ret = $.parseJSON(response.responseText);

							if (ret.response == true) {
								$('#div_form_project').dialog('close');
								$('#div_form_project').dialog('destroy');
								$('.projects_list').append(
										'<li>' + 
											'<a href="#' + name + '" class="project" value="' + ret.id + '">' + 
												name +
											'</a>' +
											'<a href="#" class="edit_project" value="' + ret.id + '">' +
												'<span class="right glyphicon glyphicon-edit"></span>' +
											'</a>' +
										'</li>');
								project_selected_id = ret.id;
								$('.project[value="' + ret.id + '"]').click();
							}
						}
					});
		});
// SUBMIT NEW TASK
$(document).on(
		'submit',
		'.create_task_form',
		function(e) {
			e.preventDefault();
			abrir_cargando();
			var input_name = $(this).find('[name="title"]');
			var title = $(input_name).val();
			
			var matches = title.match(/\+\w*[^\+]/mg);
			var users = [];
			if (matches) {
				for ( var i = 0; i < matches.length; i++) {
					users[i] = matches[i].replace(asign_user, '').trim();
					title = title.replace(matches[i],'');
				}
			}
			title = title.trim();
			
			$.post('task/add', {
				'title' : title,
				'users' : users,
				'project_id' : project_selected_id
			})
					.complete(
							function(response, status) {
								cerrar_cargando();
								if (status == 'success') {
									var ret = $.parseJSON(response.responseText);
									if (ret.response == true) {
										$(input_name).val('');
										$('.tasks_list').append(
												task_in_list(ret.id, title,
														ret.users, ret.status, ret.created));
										$('.new_task').focus();
									}
								}
								add_tooltip();
							});
		});
// CLICK SELECT PROJECT
$(document).on(
		'click',
		'.project',
		function(e) {
			e.preventDefault();
			abrir_cargando();
			project_selected_id = $(this).attr('value');
			var title_project = $(this).html();

			$('.tasks_list').html('');
			// cerrar el detalle de la tarea
			$('.detail_task').animate({
				left: "slide",
			    width: "hide",
			});
			
			$('.project').closest('li').removeClass('active');
			$(this).closest('li').addClass('active');

			var id = $(this).attr('value');
			$.post('task/list', {
				'id' : id
			}).complete(
					function(response, status) {
					    if ($('#loguear',jQuery.parseHTML(response.responseText)).length > 0) {
					        window.location = 'usuario/loguear';
					        return false;
					    }
					    
						cerrar_cargando();
						if (status == 'success') {
							var ret = $.parseJSON(response.responseText);
							if (ret.response == true) {

								for ( var i = 0; i < ret.tasks.length; i++) {
									$('.tasks_list').append(
											task_in_list(ret.tasks[i].id,
													ret.tasks[i].title,
													ret.tasks[i].users,
													ret.tasks[i].status, ret.tasks[i].created));
								}
							}
							location.hash = title_project;
						}
						
						$('.detail_task').html('');
						add_tooltip();
					});
});

function task_in_list(id, title, users, status_id, created) {
	
	var class_status = '';
	var icon = '';
	switch(status_id) {
	case 1:
	default:
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
	var ret = '<li value="'+ id +'" class="' + class_status + '">'
			+ '<a href="#" class="show_status glyphicon ' + icon +'" value="' + status + '" ></a> '
			+ '<span class="title">' + title + '</span><div class="task_users">';

	for ( var i = 0; i < users.length; i++) {
		ret += '<a href="#" class="right user">' + users[i] + '</a>';
	}
	
	var date = new Date(Date(created));
	var current_date = new Date(Date());
	
	var anio = '';
	if (date.getFullYear() != current_date.getFullYear()) {
	    anio = ' de ' + date.getFullYear(); 
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
	
	ret +='<span class="right created">' + dia + mes + anio  +'</span>';
	
	ret += '</div></li>';

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
		if (status == 'success' && response.responseText) {
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
		if (status == 'success') {
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

// AUTO CLICK SELECT PROJECT
$(document).ready(function(e) {
	if (location.hash) {
		$('.projects_list').find('[href="' + location.hash + '"]').click();
	} else {
		$('.project:first').click();
	}
	$(".new_task").autocomplete({
		source : function(request, response) {
			// Si puso '+user_name' o '+'
			if (
				request.term.match(/\+\w*[^\+]/mg) ||
				$(".new_task").val()[$(".new_task").val().length-1] == asign_user
			) {
								
				var matches = request.term.match(/\+\w*[^\+]/mg);
				
				var discard = [];
				if (matches) {
					for ( var i = 0; i < matches.length; i++) {
						if (i == (matches.length-1)) {
							// Si es '+' es porque el ultimo match es discard
							if ($(".new_task").val()[$(".new_task").val().length-1] == asign_user) {
								discard[i] = matches[i].replace(asign_user,'').trim();
							}
							request.term = matches[i].replace(asign_user,'');						
						} else {
							discard[i] = matches[i].replace(asign_user,'').trim();						
						}
					}
				}
					
				//Si puso '+'
				if ($(".new_task").val()[$(".new_task").val().length-1] == asign_user) {
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
						ultimos_buscados = data;
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
				$(".new_task").autocomplete('close');
			}
			
		},
		minLength : 1,
		select : function(event, ui) {
			console.log('select');
			var value = $(".new_task").val();
			var regex = new RegExp('\\+[\\w]*$');
			
			$(".new_task").val(value.replace(regex, asign_user + ui.item.label));
			return false;
		},
		focus : function(event, ui) {
			console.log('focus');
			var value = $(".new_task").val();
			var regex = new RegExp('\\+[\\w]*$');
			
			$(".new_task").val(value.replace(regex, asign_user + ui.item.label));					
			
			return false;
		},
		response : function(event, ui) {
		}
	});
});

//CLICK CHANGE STATUS
$(document).on('click', '.change_status', function(e) {
	e.preventDefault();
	abrir_cargando();
	var id = $(this).attr('value');
	var descripcion = $(this).attr('id');
	
	$.post('task/changestatus', {
		id : id,
		status: descripcion
	}).complete(function(response, status) {
		cerrar_cargando();
		if (status == 'success') {
			var ret = $.parseJSON(response.responseText);
			if (ret.response) {
				var li = $('.tasks_list').find('li[value="'+ id +'"]');
				
				switch(descripcion) {
					case 'Abierto':
						li.removeClass('background_done');
						li.find('a.show_status').removeClass('glyphicon-ok-circle');
						li.removeClass('background_started');
						li.find('a.show_status').removeClass('glyphicon-play-circle');
						li.addClass('background_openned');
						li.find('a.show_status').addClass('glyphicon-record');
						break;
					case 'Terminado':
						li.removeClass('background_openned');
						li.find('a.show_status').removeClass('glyphicon-record');
						li.removeClass('background_started');
						li.find('a.show_status').removeClass('glyphicon-play-circle');
						li.addClass('background_done');
						li.find('a.show_status').addClass('glyphicon-ok-circle');
						break;
					case 'Empezado':
						li.removeClass('background_openned');
						li.find('a.show_status').removeClass('glyphicon-record');
						li.removeClass('background_done');
						li.find('a.show_status').removeClass('glyphicon-ok-circle');
						li.addClass('background_started');
						li.find('a.show_status').addClass('glyphicon-play-circle');
						break;
				}
				
				li.find('a.show_status').attr('value', descripcion);
				
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
						'<li><span class="glyphicon glyphicon-play-circle"></span><a href="#" class="change_status" id="Empezado" value="'+task_id+'">Empezada</a></li>' +
						'<li><span class="glyphicon glyphicon-ok-circle"></span><a href="#" class="change_status" id="Terminado" value="'+task_id+'">Hecha</a></li>';
					break;
				case 'Terminado':
					html_tooltip += 
						'<li><span class="glyphicon glyphicon-record"></span><a href="#" class="change_status" id="Abierto" value="'+task_id+'">Abierta</a></li>' +
						'<li><span class="glyphicon glyphicon-play-circle"></span><a href="#" class="change_status" id="Empezado" value="'+task_id+'">Empezada</a></li>';
					break;
				case 'Empezado':
					html_tooltip += 
						'<li><span class="glyphicon glyphicon-record"></span><a href="#" class="change_status" id="Abierto" value="'+task_id+'">Abierta</a></li>' +
						'<li><span class="glyphicon glyphicon-ok-circle"></span><a href="#" class="change_status" id="Terminado" value="'+task_id+'">Hecha</a></li>';
					break;
			}
				
			html_tooltip += '</ul></div>';
			origin.tooltipster('content', html_tooltip);
		},
		content: ''
	});
}
var ultimo_selected_task = null;
$(document).on('click', '.tasks_list li .title', function(e) {
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

function show_task_detail(id, li) {
$('.detail_task').html('');
	
	$.post(
			base_url + '/task/view', 
			{
				id : id
			}
	).done(function(response) {
		$('.detail_task').html(response);
		if ($('.detail_task').is(':visible') && 
			$('.detail_task').find('.remove').attr('value') == id) {
			
			$('.tasks_list li').removeClass('selected');
			li.addClass('selected');
		}
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

//SUBMIT EDIT TASK
$(document).on('keypress', '.title_view_form', function(e) {
	
	if (e.keyCode == 13) {
		e.preventDefault();
		$(this).blur();
	}
});
$(document).on('blur', '.title_view_form', function(e) {
	
	var input = $(this).find('[name="title"]');
	var title = input.val();
	$.post(
		base_url + '/task/edit', 
		{
			id : ultimo_selected_task,
			title : title
		},
		'json'
	).done(function(response) {
		if (response) {
			$('.tasks_list li[value="'+ ultimo_selected_task + '"] .title').html(title);
			// TODO: cambiar de color
		} else {
			// TODO: ERROR
		}
	}).fail(function() {
		// TODO: ERROR
	});
});

$(document).keyup(function(e){
	if (prevent_close_detail) {
		prevent_close_detail = false;
		return;
	}
	// SCAPE CODE
	if(e.keyCode == 27) {
		if ($('.detail_task #view_task').length != 0) {
			$('.detail_task').animate({
				left: "slide",
			    width: "toggle",
			});
		}
	}
});

//CLICK SELECT STATUS
$(document).on(
		'click',
		'.search_status',
		function(e) {
			e.preventDefault();
			abrir_cargando();

			$('.tasks_list').html('');
			// cerrar el detalle de la tarea
			$('.detail_task').animate({
				left: "slide",
			    width: "hide",
			});
			var status_id = $(this).attr('id');
			
			$.post('task/list', {
				'id' : project_selected_id,
				'status_id' : status_id 
			}).complete(function(response, status) {
						cerrar_cargando();
						if (status == 'success') {
							var ret = $.parseJSON(response.responseText);
							if (ret.response == true) {

								for ( var i = 0; i < ret.tasks.length; i++) {
									$('.tasks_list').append(
											task_in_list(ret.tasks[i].id,
													ret.tasks[i].title,
													ret.tasks[i].users,
													ret.tasks[i].status,
													ret.tasks[i].created));
								}
							}
							// agregar el estado
							location.hash = location.hash + '/';
						}
						add_tooltip();
			});
});

// CLICK USER
$(document).on('click', '.users_list .user', function(e) {
	e.preventDefault();
	abrir_cargando();
	$.post('task/list', {
		'id' : project_selected_id,
		'status_id' : status_id 
	}).complete(function(response, status) {
		cerrar_cargando();
	});
});