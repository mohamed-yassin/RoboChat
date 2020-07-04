<?php

function talk_to_bot( $data ) {
    $msgs = $data['msgs'];
    $msgs = array('1', '2'); 
    $sub =  $data['sub'];
    $sub =  13 ; 
    $options = get_option('roboChat_settings');
    $chat_box =  $options['sub_'.$sub.'_chatbox'];

    foreach ($msgs as $key => $msg) {
      $msg =  demo_chat_api_msg();

      $parametars['phone'] =  pure_phone($msg->author);

      if($chat_box >  0){
        $parametars['body'] = "auto response from sub_$sub on msg ". $msg->body  . "  from number " . $parametars['phone'] . " through the chatbox  $chat_box ";
      }else {
        $parametars['body'] = "if you recieved this msg , that mean that no chat box selected in the dashboard , so in future we will not send a message and leave this role to customer services" ;
      }
      return whatsapp_send_messege($sub,$parametars,'chat');
    }
  }
