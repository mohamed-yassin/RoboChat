<?php 

require autoloader_path ;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

function socket_connection($data){
	$version    = new Version2X(societ_link);
	$client     = new  Client($version);
	$client->initialize();
	$client->emit("new_order" , $data );
	$client->close();
}

function send_instant_msg() {
	$msg  = $_POST['msg'];
	$sign = $_POST['sign'] ;
	$num = $_POST['num'];
	$sub = $_POST['sub'];

	if($sign == 1){
		$msg =  "$msg \n\n   #{{current_user}}" ;	
	}
	$msg = translate_short_codes($msg,$num);
	whatsapp_send_messege($msg,$num,$sub);
	die();
}

function update_session()
{
	$contact = (string)$_REQUEST['contact']; 
	$user = $_REQUEST['user'];
	$sub = $_REQUEST['sub']; 
	$sessions   = get_current_sessions($sub);

	if ( ! isset($sessions[$contact] )    ){              // allow the user to serve the client which is not served  
		$date = time();  
		$sessions[$contact] = array($user , $date )  ;		
		update_current_sessions($sub,$sessions);
		$serving_user = $user ;
	}elseif ($sessions[$contact][0] == $user ) {            // the user free the client
		unset($sessions[$contact])  ;
		update_current_sessions($sub,$sessions);
		$serving_user = '0' ;
	}else {
		$date = time();  
		$time_def =  $date - $sessions[$contact][1];
		if($time_def >  session_duration ){                // if the user didn't served with the client for a long time , free the client 
			$sessions[$contact] = array($user , $date )  ;
			update_current_sessions($sub,$sessions);
			$serving_user = $user ;
		}else {                                            // the client is served by another user              
			$serving_user =  $sessions[$contact][0] ;
		}
	}

	$data =  array('action' => 'update_session'  ,'contact' => $contact , 'user' => $serving_user); 
	socket_connection($data);
}