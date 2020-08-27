<?php 
function chatapi_sub_status($api,$token){
    $login =  whatsapp_login($api,$token);
    return  $login ;
}
function whatsapp_login($api,$token)
{
    $url =   $api.'status?token='.$token;
    $result = @file_get_contents($url);
    $result = (array)json_decode($result);

    return $result;
}
function whatsapp_messeges($api,$token,$filters= array())
{
    if(! isset($filters['lastMessageNumber'])){
        $filters['last'] = 'true'; 
        $filters['limit'] = '1000';   
    }

    $filter_as_string = "";
    foreach ((array)$filters as $filter => $value) {
        if($value  != ''){
            $filter_as_string .=  "&$filter=$value";
        }
    }
    $url = $api.'messages?token='.$token.$filter_as_string;
    $result = file_get_contents($url);
    $result = json_decode($result);
    return $result;
}
function whatsapp_send_messege($sub,$parametars, $type = 'chat' )
{
    $sub_connection_data = sub_connection_data($sub);

    if($sub_connection_data['api'] == '' || $sub_connection_data['token'] == ''){
        return array('errors' => 'Please call admin to re-check your data' ); 
    }

    if($sub_connection_data['msgs']  && $sub_connection_data['msgs'] >  0 ){
        if($type == 'file'){
            $url =  $sub_connection_data['api'].'sendFile?token='.$sub_connection_data['token'];
        }else {
            $url =  $sub_connection_data['api'].'sendMessage?token='.$sub_connection_data['token'];
            $response['msg'] =  restore_new_line($parametars['body']) ; 
        }
        $json = json_encode($parametars); // Encode data to JSON
        $options = stream_context_create(['http' => 
            [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);
        $response   = file_get_contents($url, false, $options);
        $response   = json_decode($response, TRUE);
        if(isset($response['sent']) && $response['sent'] ==  1 ){ // sent successfully 
            $response['status'] = 1;
            $response['balance'] = reduce_msgs_counter($sub);
        }else {
            $response['status'] =  '3';
            sub_errors_log($sub,$response);
            $response['errors'] =  'Please call admin to re-check your data';
        }
    }else {
        $response =  array('status' => "2" , 'balance' => 0 );  // if daily msg balance is ranout
        sub_errors_log($sub,$response);
    }
    return $response;
}
function whatsapp_log_out($api,$token,$sub = 0)
{
    $url = $api.'logout?token='.$token;
    $data = [
    ];
    $json = json_encode($data); // Encode data to JSON

    // Make a POST request
    $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $json
        ]
    ]);
    // Send a request
    $result = array();
    $result = file_get_contents($url, false, $options);
    if($sub != 0){
        echo '<a href="'.admin_url('admin.php?page='.$sub.'&process=log_out').'" class="button button-primary" > انهاء الجلسه </a>';
    }else {
        return $result;
    }
}

/*
    [action] => chat_api_main_processes_action
    [api] => https://eu2.chat-api.com/instance9535/
    [token] => m7pwv0z8lr2t5t08
    [process] => reboot
*/
function chat_api_whatsapp_connect($api,$token){
    $url = $api.'status?full=true&no_wakeup=true&token='.$token;
    return file_get_contents($url);
}
function chat_api_whatsapp_reboot($api,$token){
    $url = $api.'reboot?token='.$token;
    return file_get_contents($url);
}

function chat_api_main_processes(){
    $api        =  $_POST['api'];
    $token      =  $_POST['token'];
    $process    =  $_POST['process'];
    $result     =  array();
    $post       =  $_POST['post'];
    if($process == 'connect' ){
        $result = chat_api_whatsapp_connect($api,$token);
        $result_array =  json_decode($result ,true);
        if(isset($result_array['accountStatus']) && $result_array['accountStatus'] == 'got qr code'){
            echo 'افحص الكود بالموبايل ثم اعد الضغط علي الزر';
            echo '<img src="'.$result_array['qrCode'].'" alt="Base64 encoded image"/>';    
        }
    }elseif ($process == 'disconnect' ){
        $result = whatsapp_log_out($api,$token);
    }elseif($process == 'reboot') {
        echo 'يناء علي chat api فانه لن يتم توصيل عمليات علي السيرفر لمده 5 دقايق من الان وستكون كل ردود الapi غير مكتمله </br>';
        $result = chat_api_whatsapp_reboot($api,$token);
    }elseif($process == 'reactive') {
        $my_sub = array(
            'ID'           => $post,
            'post_status' => 'wc-active',
        );
        echo  wp_update_post( $my_sub ) >  0  ?  "تم تجديد الاشتراك"  :  "هناك مشكله في التحديث";
    }

    $result = @json_decode($result,true);
    pre($result);
    die();
}
function make_response_ul($ul,$ul_name=''){
    if(is_array($ul)){
        echo "$ul_name<ul>";
        foreach ($ul as $key => $value) {
            if(is_array($value)){
                make_response_ul($value,$key);
            }else {
                echo "<li>$key : $value</li></br>";
            }
        }
        echo "</ul>";    
    }else {
        echo "</br><li>$ul</li></br>";
    }
}
function chat_api_set_webhock($api,$token,$hook)
{
    $url = $api.'webhook?token='.$token;
    $data = array( 'webhookUrl' => $hook );
    $json = json_encode($data); // Encode data to JSON

    // Make a POST request
    $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $json
        ]
    ]);
    // Send a request
    $result = array();
    return  file_get_contents($url, false, $options);
}
function demo_chat_api_msg(){
    $msg = new \stdClass();
	$msg->id 			= 'true_966599950800@c.us_3EB01A731400C85F8867';
	$msg->body 			= 'رساله تجريبيه';
	$msg->fromMe 		= 1;
	$msg->author 		= '201096808707@c.us';
	$msg->time 			= 1564611595;
	$msg->chatId 		= '966599950800@c.us';
	$msg->messageNumber = 23;
	$msg->type	 		= 'chat';
	$msg->senderName 	= 'Business Expert';
	$msg->self 			= '0';
	$msg->isForwarded 	= '0';
	$msg->quotedMsgBody = '';
	$msg->quotedMsgId 	= '';
	$msg->chatName 		= '+966 59 995 0800';
	return $msg ;
}

function do_chat_api_api($api,$token,$endpoint,$parameters){
    $sub_connection_data = sub_connection_data($sub);

    if($sub_connection_data['api'] == '' || $sub_connection_data['token'] == ''){
        return array('errors' => 'Please call admin to re-check your data' ); 
    }

    if($sub_connection_data['msgs']  && $sub_connection_data['msgs'] >  0 ){
        if($type == 'file'){
            $url =  $sub_connection_data['api'].'sendFile?token='.$sub_connection_data['token'];
        }else {
            $url =  $sub_connection_data['api'].'sendMessage?token='.$sub_connection_data['token'];
            $response['msg'] =  restore_new_line($parametars['body']) ; 
        }
        $json = json_encode($parametars); // Encode data to JSON
        $options = stream_context_create(['http' => 
            [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);
        $response   = file_get_contents($url, false, $options);
        $response   = json_decode($response, TRUE);
        if(isset($response['sent']) && $response['sent'] ==  1 ){ // sent successfully 
            $response['status'] = 1;
            $response['balance'] = reduce_msgs_counter($sub);
        }else {
            $response['status'] =  '3';
            sub_errors_log($sub,$response);
            $response['errors'] =  'Please call admin to re-check your data';
        }
    }else {
        $response =  array('status' => "2" , 'balance' => 0 );  // if daily msg balance is ranout
        sub_errors_log($sub,$response);
    }
    return $response;
}
function can_i_do_api($sub){
    //  
}
function chat_api_check_connection_status(){
    $working = 1; // 1 : it's ok ,  0 : something went wrong 
    $sub 				 = $_POST['sub'];
	$woocommerce_sub	 = get_post_data_from_the_main_blog($sub);
    $woocommerce_status	 = $woocommerce_sub->post_status;

	if($woocommerce_status == 'wc-active'){
		$sub_connection_data = sub_connection_data($sub);
		$api   				 = $sub_connection_data['api'];
		$token 				 = $sub_connection_data['token'];
        
        $chatapi_sub_status =  chatapi_sub_status($api,$token);
        if(! isset($chatapi_sub_status['accountStatus']) || $chatapi_sub_status['accountStatus'] != 'authenticated'){
            $working = 0 ;
        }
    }else {
        $working = 0 ;
    }

    echo $working ;
    exit; 
}