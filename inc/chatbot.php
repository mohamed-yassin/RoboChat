<?php
function talk_to_bot( $data ) {
  define('test' , true);
  $sub =  $data['sub'];
  if($sub >  0){
    if(isset($data['messages']) || test == true){
      $msgs = $data['messages'];
      
      if(is_array($msgs) &&  count($msgs) >  0){
        $options = get_option('roboChat_settings');
        $chat_box =  $options['sub_'.$sub.'_chatbox'];
        if($chat_box  >  0){
          foreach ($msgs as $key => $msg) {
            if(($msg['fromMe'] != 1 && $msg['fromMe'] != 'true' ) &&  code_from_response($msg['body']) == 1  ){
              $parametars['body'] = "auto answer from sub_$sub on msg ".$msg['body']." from number " . $msg['author'] . " through the chatbox  $chat_box ";
              $parametars['phone'] =  pure_phone($msg['author']);
              return whatsapp_send_messege($sub,$parametars,'chat');
            }else {
              $parametars['body'] = "test error msg " .  $msg['body'];
              $parametars['phone'] =  pure_phone($msg['author']);
              // whatsapp_send_messege($sub,$parametars,'chat');
              return 0;
            }
          }
        }
      }
    }
  }
}

function code_from_response($resp){
  $codes= array(
    1     => 1,
    '1'   => 1,
    '١'   => 1,
    ١   => 1,
  );
  return isset($codes[$resp]) ?  $codes[$resp] : '' ; 
}