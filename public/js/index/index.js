var asign_user = '+';
var prevent_close_detail = false;
var project_selected_id = null;

$(document).ready(function(e) {
	if (typeof location.search == "undefined") {
		alert('Tu navegador no es soportado');
	}
	
	// AUTO CLICK SELECT PROJECT
	if (location.search === "") {
		
		if (location.hash) {
			$('.projects_list').find('[href="' + location.hash + '"]').click();
		} else {
			$('.project:first').click();
		}
	}

	$('.tasks_list').sortable({
		axis: 'y',
		over: function( event, ui ) {
			$(ui.item).addClass('drag');
		},
		out: function( event, ui ) {
			$(ui.item).removeClass('drag');
		},
		update: function( event, ui ) {
			var sort;

			sort = $(ui.item).index() + 1;
		}
	});
	
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
											'</a>&nbsp;' +
											'<span class="badge closed">0</span>' +
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
			
			$.post(base_url + '/task/add', {
				'title' : title,
				'users' : users,
				'project_id' : project_selected_id
			}).complete(
				function(response, status) {
					var ret,
						badge_project,
						count_task_openned;

					cerrar_cargando();
					if (status == 'success') {
						ret = $.parseJSON(response.responseText);
						if (ret.response === true) {
							$(input_name).val('');
							$('.tasks_list').append(
								task_in_list(
									ret.id, 
									title,
									ret.users, 
									ret.status,
									ret.created,
									ret.sort
								)
							);
							$('.new_task').focus();
							badge_project = $('.project[value="' + project_selected_id + '"]').parent().find('span.badge');
							count_task_openned = badge_project.html();
							
							badge_project.html(parseInt(count_task_openned)+1);
							if (count_task_openned == "0") {
								badge_project.removeClass('closed');
								badge_project.addClass('openned');
							}
						}
					}
					add_tooltip();
				}
			);
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

			var project_id = $(this).attr('value');
			$.post(base_url + '/task/list', {
				'project_id' : project_id,
				'status_id' : 1  // MOSTRAR LAS ABIERTAS
			}).complete(
					function(response, status) {
					    if ($('#loguear',jQuery.parseHTML(response.responseText)).length > 0) {
					        window.location = 'usuario/loguear';
					        return false;
					    }
					    
						cerrar_cargando();
						if (status == 'success') {
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

							}
							location.hash = title_project;
							
							$(".search_status").removeClass('active');
							$(".search_status[id=" + ret.status_id + "]").addClass('active');
						}
						
						$('.detail_task').html('');
						add_tooltip();
					});
});

function task_in_list(id, title, users, status_id, created, sort) {
	
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
		if (status == 'success') {
			var ret = $.parseJSON(response.responseText);
			if (ret.response) {
				
				var li = change_status_task(task_id, descripcion);
				
				if (typeof websocket !== 'undefined' && websocket.readyState == websocket.OPEN) {
				    
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

// SHOW DETAIL TASK
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
    $('.detail_task').html('<div class="cargando"><img src="' + base_url + '/public/img/cargando.gif"></div>');
	
    
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
						
						if (count_task_openned == '1') {
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
			$('.tasks_list li[value="'+ tmp_selected_task + '"] .title').html(title);
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
});


//SUBMIT EDIT TITLE'S TASK
$(document).on('keypress', '.title_view_form', function(e) {
	
	if (e.keyCode == 13) {
		e.preventDefault();
		$(this).blur();
	}
});

//SUBMIT EDIT TASK
$(document).on('keypress', '.description_view_form', function(e) {
	if (e.keyCode == 13 && e.shiftKey == false) {
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
});

$(document).keyup(function(e){
	if (prevent_close_detail) {
		prevent_close_detail = false;
		return;
	}
	// SCAPE CODE
	if(e.keyCode == 27) {
		if ($('.detail_task #view_task').length !== 0) {
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
			var status_id;
			e.preventDefault();
			abrir_cargando();

			$('.tasks_list').html('');
			// cerrar el detalle de la tarea
			$('.detail_task').animate({
				left: "slide",
			    width: "hide",
			});
			status_id = $(this).attr('id');
			
			$.post(base_url + '/task/list', {
				'project_id' : project_selected_id,
				'status_id' : status_id 
			}).complete(function(response, status) {
				var ret,
					i;
				cerrar_cargando();
				if (status == 'success') {
					ret = $.parseJSON(response.responseText);
					if (ret.response === true) {

						for (i = 0; i < ret.tasks.length; i++) {
							$('.tasks_list').append(
								task_in_list(
									ret.tasks[i].id,
									ret.tasks[i].title,
									ret.tasks[i].users,
									ret.tasks[i].status,
									ret.tasks[i].created,
									ret.tasks[i].sort
								));
						}
					}
					// agregar el estado
					//location.hash = location.hash + '/';
					
					$(".search_status").removeClass('active');
					if (ret.status_id === null) {
					    // ver como dejar active el ALL 
					} else {
					    $(".search_status[id=" + ret.status_id + "]").addClass('active');
					    
					}
				}
				add_tooltip();
			});
});

// CLICK USER
$(document).on('click', '.users_list .user', function(e) {
	e.preventDefault();
	abrir_cargando();
	
	var user_id = $(this).attr('value');
	var user = $(this).html();
	$.post(base_url + '/task/list', {
		'user_id' : user_id 
	}).complete(function(response, status) {
		var ret,
			i;
			cerrar_cargando();
			$('.tasks_list').html('');
			if (status == 'success') {
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
				location.hash = 'user:' + user;
			}
			
			$('.detail_task').html('');
			add_tooltip();
	});
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
		if (status == 'success') {
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
		if (status == 'success' && response.responseText) {
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
				if (status == 'success') {
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