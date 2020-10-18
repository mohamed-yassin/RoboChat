<?php
function send_instant_msg() {
	if($_POST['_wpnonce'] == robo_nonce()){
		$msg 	= $_POST['msg'];
		$sign 	= $_POST['sign'] ;
		$num 	= $_POST['num'];
		$sub    = $_POST['sub'];
		$files 	= $_POST['files'];
	}
	if( $_POST['_wpnonce'] == robo_nonce() ){
		$parametars['phone'] = $num ;
		if(strlen($files) >  0){ // if has media files 
			$files =  explode('&&&&&', $files);
			foreach($files as $key => $media_id){
				$media_id =  $files[$key] ; 
				if($media_id >  0){
					$parametars['body']	  	= file_url($media_id);
					$parametars['caption']  = translate_short_codes($msg,$num);
					$parametars['filename'] = get_the_title($media_id);
					if($sign == 0){
						$parametars['caption'] =  translate_short_codes($parametars['caption'] . " \n\n   *{{current_user}}*", $num) ;
						$sign = 2 ;	
					}		
					// in sending we use "file" as type according to chat api , but in recieving we use image
					$chat_api_response 	= whatsapp_send_messege($sub,$parametars, $type = 'file') ;
					$msgs[$media_id]= (object)array(
						'id'		=> $chat_api_response['id'],
						'body' 		=> $parametars['body'],
						'fromMe'	=> 1,
						'type'		=> 'image',
						'time'		=> time(),
						'chatId' 	=> $num.'@c.us',
						'author' 	=> $num.'@c.us',
						'caption'	=> $parametars['caption'],
						'fake'		=> 1,
					);	
				}
			}
			$response['balance'] =  $chat_api_response['balance'];
			//$response['msgs'] =  prepare_msgs($msgs);  // stop it because of 
		}else {
			if($sign == 0 ){
				$msg =  $msg . " \n\n   *{{current_user}}* " ;
			}		

			$parametars['body'] 		= translate_short_codes($msg,$num);
			$chat_api_response = whatsapp_send_messege($sub,$parametars, $type = 'chat') ;
			$msgs[0]= (object)array(
				'id'		=> $chat_api_response['id'],
				'body' 		=> $parametars['body'],
				'fromMe'	=> 1,
				'type'		=> 'chat',
				'time'		=> time(),
				'chatId' 	=> $num.'@c.us',
				'author' 	=> $num.'@c.us',
				'fake'		=> 1,
			);
			$response['balance'] =  $chat_api_response['balance'];
			//$response['msgs'] =  prepare_msgs($msgs);
		}
	}
	json_response($response);
}
function update_session()
{
	$client = (string)$_REQUEST['client']; 
	$user 	 = get_current_user_id();
	$sub 	 = $_REQUEST['sub'];  // need to be more secure 
	$sessions   = get_current_sessions($sub);
	$date = time();  

	if ( ! isset($sessions[$client])   ||  $sessions[$client][0] == 0  ){              // allow the user to serve the client which is not served  
		$sessions[$client] = array($user , $date)  ;		
	}elseif ($sessions[$client][0] == $user ) {            // the user free the client
		$sessions[$client] = array( 0, $date ) ;
	}else {
		$time_def =  $date - $sessions[$client][1];
		if($time_def >  session_duration ){                // if the user didn't served with the client for a long time , free the client 
			$sessions[$client] = array(0 , $date )  ;
		}
	}

	update_current_sessions($sub,$sessions);


	$sessions = sessions_for_current_user($sub);
	json_response($sessions);
}

function update_data($keep_listingn = false , $out_response = array()){
	$sub 				 = $_POST['sub'];
	$sub_connection_data = sub_connection_data($sub);
	$api   				 = $sub_connection_data['api'];
	$token 				 = $sub_connection_data['token'];
	$last_message_number = $_POST['last_message_number'];
	$_nonce				 =  $_POST['_wpnonce'] ; 

	$filters			 = array('lastMessageNumber' =>$last_message_number);
	if(2 > 1){

		// New Messages After the $last_message_number
		$whatsapp_messeges 	 = whatsapp_messeges($api,$token,$filters);
		if(isset($whatsapp_messeges->lastMessageNumber)){
			$response['msgs'] 				 = prepare_msgs($whatsapp_messeges->messages);
			$response['last_message_number'] = $whatsapp_messeges->lastMessageNumber ;	
		}else {
			if($keep_listingn ==  true){
				sleep(1);
				if(count($out_response) >  1){
					foreach ($out_response as $response_key => $response_value) {
						$response[$response_key] =  $response_value ;
					}
				}
				update_data($keep_listingn, $response);	
			}
		}
		// Sessions
		$response['sessions'] = sessions_for_current_user($_POST['sub']);
	}else {
		echo  " not verified ";
	}
	
	json_response($response);
}