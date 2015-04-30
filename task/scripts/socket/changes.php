<?php
const PORT = '8000';
const FILE_NAME = 'status.txt';

$host = 'localhost' ;

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

socket_bind($socket, $host, PORT);

socket_listen($socket);

$clients = array($socket);

$content = read_file();

//start endless loop, so that our script doesn't stop
while ($content == 'run') {
	
	$changed = $clients;
	socket_select($changed, $null, $null, 0,2);
	//check for new socket
	if (in_array($socket, $changed)) {
		$socket_new = socket_accept($socket);
		$clients[] = $socket_new;
		
		$header = socket_read($socket_new, 5000);
		perform_handshaking($header, $socket_new, $host, PORT);
// 		socket_getpeername($socket_new, $ip); //get ip address of connected socket
		/**
		$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected'))); //prepare json data
		send_message($response); //notify all users about new connection
		*/
		//make room for new socket
		$found_socket = array_search($socket, $changed);
		unset($changed[$found_socket]);
	}


	foreach ($changed as $changed_socket) {

		//check for any incomming data
		while(@socket_recv($changed_socket, $buf, 1024, 0) >= 1)
		{
			$received_text = unmask($buf); //unmask data
			$tst_msg = json_decode($received_text); //json decode
			if (is_object($tst_msg)) {
				
				$response_text = mask(json_encode(
						array(
								'type'=>    $tst_msg->type,
								'name'=>    $tst_msg->name,
								'message'=> $tst_msg->message,
								'count_task_openned'=> $tst_msg->count_task_openned,
								'project_id'=> $tst_msg->project_id,
						)
				));

				send_message($response_text); //send data
				break 2;
			} else {
				// Si envia un dato vacio asumimos que se cerró la conexion
				if ($received_text == "") {
					$found_socket = array_search($changed_socket, $clients);
					unset($clients[$found_socket]);
					unset($changed[$found_socket]);
					break 2;
				}
			}
		}

		$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
		if ($buf === false) { // check disconnected client
			// remove client for $clients array
			$found_socket = array_search($changed_socket, $clients);
			unset($clients[$found_socket]);
			unset($changed[$found_socket]);

			/**
			//notify all users about disconnected connection
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
			send_message($response);
			*/
		}
	}
	$content = read_file();
}
// close the listening socket
socket_close($socket);

function send_message($msg)
{
	global $clients;
	foreach($clients as $changed_socket)
	{
		@socket_write($changed_socket,$msg,strlen($msg));
	}
	return true;
}


//Unmask incoming framed message
function unmask($text) {
	$length = ord($text[1]) & 127;
	if($length == 126) {
		$masks = substr($text, 4, 4);
		$data = substr($text, 8);
	}
	elseif($length == 127) {
		$masks = substr($text, 10, 4);
		$data = substr($text, 14);
	}
	else {
		$masks = substr($text, 2, 4);
		$data = substr($text, 6);
	}
	$text = "";
	for ($i = 0; $i < strlen($data); ++$i) {
		$text .= $data[$i] ^ $masks[$i%4];
	}
	return $text;
}

//Encode message for transfer to client.
function mask($text)
{
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);

	if($length <= 125)
		$header = pack('CC', $b1, $length);
	elseif($length > 125 && $length < 65536)
	$header = pack('CCn', $b1, 126, $length);
	elseif($length >= 65536)
	$header = pack('CCNN', $b1, 127, $length);
	return $header.$text;
}

//handshake new client.
function perform_handshaking($receved_header,$client_conn, $host, $port)
{
	var_dump($receved_header);
	$headers = array();
	$lines = preg_split("/\r\n/", $receved_header);
	foreach($lines as $line)
	{
		$line = chop($line);
		if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
		{
			$headers[$matches[1]] = $matches[2];
		}
	}
	
	$secKey = '';
	if (isset($headers['Sec-WebSocket-Key'])) {
		$secKey = $headers['Sec-WebSocket-Key'];
	}
	
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	
	// Accept la coneccion del nuevo cliente, sino queda la coneccion a CONNECTING
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
			"Upgrade: websocket\r\n" .
			"Connection: Upgrade\r\n" .
			"WebSocket-Origin: http://$host\r\n" .
			"WebSocket-Location: ws://$host:$port\r\n".
			"Sec-WebSocket-Accept:$secAccept\r\n";
	
	$ret = '';
	if (isset($headers['Sec-WebSocket-Protocol']))
	{
	    $ret = "Sec-WebSocket-Protocol: " . substr($path, 1) . "\r\n";
	}
	
	$upgrade .= $ret . "\r\n";
	
	socket_write($client_conn,$upgrade,strlen($upgrade));
}
/**
 * Reads file and return yours content
 * 
 * @return String
 */
function read_file() {

	$partes_ruta = pathinfo(__FILE__);
	$file_name = $partes_ruta['dirname'] . DIRECTORY_SEPARATOR . FILE_NAME;
	
	$gestor = fopen($file_name, 'r');
	$content = fread($gestor, filesize($file_name));
	fclose($gestor);
	return $content;
}