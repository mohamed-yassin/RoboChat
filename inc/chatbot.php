<?php
function talk_to_bot( $data ) {
    $msgs = $data['messages'];
    $sub =  $data['sub'];
    $options = get_option('roboChat_settings');
    $chat_box =  $options['sub_'.$sub.'_chatbox'];
    $msg =  $msgs[0];
    $msg['author'] =  pure_phone($msg['author']);
    $parametars['phone'] =  '447380339025' ; 

    if($chat_box >  0){
      $parametars['body'] = "notification from sub_$sub on msg from number " . $msg['author'] . " through the chatbox  $chat_box ";
    }else {
      $parametars['body'] = "if you recieved this msg , that mean that no chat box selected in the dashboard , so in future we will not send a message and leave this role to customer services" ;
    }
     return  whatsapp_send_messege($sub,$parametars,'chat');
  }
