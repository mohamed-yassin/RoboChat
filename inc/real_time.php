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
					$parametars['body']	  =  production !== true ? 'https://cdn.pixabay.com/user/2014/05/07/00-10-34-2_250x250.jpg'  :  wp_get_attachment_url($media_id);
					//$parametars['body']	  =  'https://cdn.pixabay.com/user/2014/05/07/00-10-34-2_250x250.jpg';
					$parametars['caption']  =  translate_short_codes($msg,$num);
					$parametars['filename']  = get_the_title($media_id);
					if($sign == 1){
						$parametars['caption'] =  $parametars['caption'] . " \n\n   #{{current_user}}" ;
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
						//'parametars'=> $parametars,
						//'response'  => $chat_api_response,
						'caption'	=> $parametars['caption'],
						'fake'		=> 1,
					);	
				}
			}
			$response['balance'] =  $chat_api_response['balance'];
			$response['msgs'] =  prepare_msgs($msgs);
		}else {
			if($sign == 1){
				$msg =  $msg . " \n\n   #{{current_user}}" ;
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
				//'parametars'=> $parametars,
				//'response'  => $chat_api_response,
				'fake'		=> 1,
			);
			$response['balance'] =  $chat_api_response['balance'];
			$response['msgs'] =  prepare_msgs($msgs);
		}

		
	}
	json_response($response);
}
function update_session()
{
	$contact = (string)$_REQUEST['contact']; 
	$user 	 = get_current_user_id();
	$sub 	 = $_REQUEST['sub'];  // need to be more secure 
	$sessions   = get_current_sessions($sub);
	$date = time();  

	if ( ! isset($sessions[$contact])   ||  $sessions[$contact][0] == 0  ){              // allow the user to serve the client which is not served  
		$sessions[$contact] = array($user , $date)  ;		
	}elseif ($sessions[$contact][0] == $user ) {            // the user free the client
		$sessions[$contact] = array( 0, $date ) ;
	}else {
		$time_def =  $date - $sessions[$contact][1];
		if($time_def >  session_duration ){                // if the user didn't served with the client for a long time , free the client 
			$sessions[$contact] = array(0 , $date )  ;
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