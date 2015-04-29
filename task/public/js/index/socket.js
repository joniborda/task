Notification.requestPermission();

//create a new WebSocket object.
var wsUri = "ws://localhost:8000/demo/server.php"; 	
websocket = new WebSocket(wsUri); 

if (typeof current_user_id == 'undefined' ) {
	var current_user_id = null;
}

websocket.onopen = function(ev) { // connection is open 
	console.log('connected');
	var msg = {
		type: 'connected',
		message: '',
		name: current_user_id
	};
	
	//convert and send data to server
	websocket.send(JSON.stringify(msg));
};

//#### Message received from server?
websocket.onmessage = function(ev) {
	var msg = JSON.parse(ev.data); //PHP sends Json data
	var type = msg.type; //message type
	var umsg = msg.message; //message text
	var uname = msg.name; //user name

	console.log("recibio un mensaje");
	
	
	switch (type) {
	    case "connected":
	        var not = {title: 'task', message: 'Usuario conectado'};
	        notificar(not);
	        
	        break;
	case "count_task_openned":
		console.log(msg);
		var not = {title: 'Tarea abierta', message: 'Usuario conectado'};
        notificar(not);
		break;

	default:
		break;
	}
	
	if(type == 'usermsg') 
	{
    	// SEGUIR ACA
    	$('#message_box').append("<div><span class=\"user_name\" style=\"color:#"+ucolor+"\">"+uname+"</span> : <span class=\"user_message\">"+umsg+"</span></div>");
	}
	if(type == 'system')
	{
		$('#message_box').append("<div class=\"system_msg\">"+umsg+"</div>");
	}
};

websocket.onerror	= function(ev){
	console.log('ocurrio un error con el socket');
}; 
websocket.onclose 	= function(ev){
	console.log('cerro conexion con el socket');
}; 

window.onbeforeunload = function() {
	if (typeof websocket != "undefined") {
		websocket.close();
	}
};