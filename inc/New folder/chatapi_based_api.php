<?php
function send_message($data){

  if(token($data['sub']) != $data['token'] ){
    return array('errors' => "Not Matching Sub & token");
  }

  $required_data =  array(
    'sub','token','phone','body'
  );

  foreach ($required_data as $info) {
    if(!isset($data[$info]) ||  $data[$info] == '' ){
      return array('errors' => "Missing date: '$info' is required ");
    }
  }
  $sub    = $data['sub'];
  $token  = $data['token'];
  $type   = $data['type'];

  $parametars['phone']    = $data['phone'] ;
  $parametars['body']      = $data['body'] ;
  $parametars['caption']  = $data['caption'] ;
  $parametars['filename'] = $data['filename'] ;

  $response =  whatsapp_send_messege($sub,$parametars,$type);  

  return $response ; 
}  
  function do_chatapi_api($end_point,$data , $need_balance =  true ){
    if(token($data['sub']) !=  $data['token']){
      return array('errors' => "Not Matching Sub & token");
    }
    
    $sub_connection_data = sub_connection_data($data['sub']);
    if($sub_connection_data['api'] == '' || $sub_connection_data['token'] == ''){
      return array('errors' => 'Please call admin to re-check your data' ); 
    }

    if($need_balance ==  true  &&   !($sub_connection_data['msgs'] >  0)){
      return array('errors' => 'out of balanece' ); 
    }

    $parametars = $data->get_params();
    unset($parametars['token']);
    $url =  $sub_connection_data['api'].$end_point.'?token='.$sub_connection_data['token'];

    $json = json_encode($parametars);
    $options = stream_context_create(['http' => 
        [
            'method'  => $_SERVER['REQUEST_METHOD'] ,
            'header'  => 'Content-type: application/json',
            'content' => $json
        ]
    ]);
    $response   = file_get_contents($url, false, $options);
    $response  =  json_decode($response, TRUE);
    return  $response ;  
  }

  //////////////////////////////// External APi ////////////////////////////////
  //---------------->  instance
  function external_api_logout($data){
    return do_chatapi_api('logout',  $data ,  false)  ;
  }
  function external_api_reboot($data){
    return do_chatapi_api('reboot',  $data ,  false)  ;
  }
  function external_api_settings_post($data){
    return do_chatapi_api('settings',  $data ,  false)  ;
  }
  function external_api_settings_get($data){
    return do_chatapi_api('settings',  $data ,  false)  ;
  }
  function external_api_status($data){
    return do_chatapi_api('status',  $data ,  false)  ;
  }


  /////////////// Messsages/
  function external_api_sendMessage($data)
  {
    return do_chatapi_api('sendMessage',  $data ,  true)  ;
  }
  function external_api_sendPTT($data)
  {
    return do_chatapi_api('sendPTT',  $data ,  true)  ;
  }
  function external_api_sendFile($data)
  {
    return do_chatapi_api('sendFile',  $data ,  true)  ;
  }
  function external_api_sendLink($data)
  {
    return do_chatapi_api('sendLink',  $data ,  true)  ;
  }
  function external_api_sendContact($data)
  {
    return do_chatapi_api('sendContact',  $data ,  true)  ;
  }
  function external_api_sendLocation($data)
  {
    return do_chatapi_api('sendLocation',  $data ,  true)  ;
  }
  function external_api_sendVCard($data)
  {
    return do_chatapi_api('sendVCard',  $data ,  true)  ;
  }
  function external_api_forwardMessage($data)
  {
    return do_chatapi_api('forwardMessage',  $data ,  true)  ;
  }
  function external_api_messages($data)
  {
    return do_chatapi_api('messages',  $data ,  true)  ;
  }
  //---------------->  Webhook
  function external_api_webhookUrl($data){
    return do_chatapi_api('webhookUrl',  $data ,  false)  ;
  }