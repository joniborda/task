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
console.log(msg);

	if (msg.name == current_user_id) {
		return;
	}
	
	switch (msg.type) {
	    case "connected":
	        var not = {title: 'task', message: 'Usuario conectado'};
//	        notificar(not);
	        break;
	    case "change_status":
	        var not = {title: 'Cambio de estado', message: msg.message, status: msg.status, user_name: msg.user_name};
	        
	        count_task_in_project(msg.count_task_openned, msg.project_id);
            notificar(not);
	        break;

	default:
		break;
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