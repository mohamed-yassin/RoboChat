<?php
function internal_api_talk_to_bot( $data ) {
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
            if( $msg['fromMe'] != 1 && $msg['fromMe'] != 'true' ){
              $parametars['body']   = bot_answer($sub, $msg);
              $parametars['phone']  = pure_phone($msg['author']) ;
              return $parametars['body'] ;
              return whatsapp_send_messege($sub,$parametars,'chat');
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
function last_interact_code_name($sub){
    return $sub . '_last_interact_code'; 
}
function zero_level_interact($lang='df'){
  // time().(lang slug , or df for defult).(numiric_path_for_the_current_suituation)
  $chatbox = active_chatbox($sub) ; 
  return time()."_".$lang."_0"; 
}
function active_chatbox($sub){
  $options = get_option('roboChat_settings');
  return $options['sub_'.$sub.'_chatbox'] >  0  ? $options['sub_'.$sub.'_chatbox'] :  0 ;
}
function  active_chatbox_info($sub , $info ){
  $chatbox =  active_chatbox($sub) ; 
  return  carbon_get_post_meta ( $chatbox , $info ) ; 
}
function last_interact_code($sub,$phone,$client_name){
  $contact            = get_contact_by_phone($phone , $client_name);
  $last_interact_code = get_post_meta( $contact, last_interact_code_name($sub) , true );
  $explode = explode('_',$last_interact_code);
  if(count($explode) >  2){ // exist
    $time = $explode[0];
    $now  = time();
    $session_duration  = (int)active_chatbox_info($sub , 'session_duration');
    $def =  $now - $time ;
    if($def > $session_duration ){
      $last_interact_code = zero_level_interact($explode[1]);
      update_post_meta( $contact , last_interact_code_name($sub) , $last_interact_code );
    }
  }else {
    $last_interact_code = '' ; 
    update_post_meta( $contact , last_interact_code_name($sub) , $last_interact_code );
  }
  return $last_interact_code  ;
}
function bot_answer_msg($selected ,  $msg_error  = ''){
  $new_line =  "\n\n";
  $new_line = "<br>"; 
  foreach ((array)$selected['childs'] as $key => $child) {
    $childs .= ($key+1) ." " . $child['header'] . $new_line ;
  }

  if($msg_error !=  ''){
    $path =  $selected['header'].$new_line.$selected['body'].$new_line.$childs.$new_line.$selected['footer'];

  }else {
    $path =  $selected['header'].$new_line.$msg_error.$new_line.$childs.$new_line.$selected['footer'];
    $path =  $msg_error ;
  }
  return   $path ;
}
function update_last_interact_code($contact , $field_name ,$lang =  'df' , $path  = ''){
  $last_interact_code = time()."_".$lang; 
  $last_interact_code = $path != '' ? $last_interact_code."_".$path :  $last_interact_code ; 
  update_post_meta( $contact , $field_name  , $last_interact_code );
  return $last_interact_code ;
}

function bot_answer($sub,$msg){
  $phone        = pure_phone($msg['author']);
  $client_name  = $msg['senderName'] ; 
  $contact      = get_contact_by_phone($phone , $client_name);
  $field_name   = last_interact_code_name($sub);
  $chatbox      = active_chatbox($sub);

  $last_interact_code = get_post_meta( $contact, $field_name , true );

  if($last_interact_code ==  ''){ // if no session
    $df_lang = active_chatbox_info($sub , 'defult_language');
    update_last_interact_code($contact , $field_name , $df_lang );

    $selected = active_chatbox_info($sub , $df_lang )[0] ;
    return bot_answer_msg( $selected ) ;
  }


  $explode    = explode('_', $last_interact_code );
  $time       = (int)$explode[0];
  $lang       = $explode[1];
  $now        = time();
  $def        = $now - $time ;
  $session    = active_chatbox_info($sub , 'session_duration' );

  if($def >  $session){ // Expired Session 
    update_last_interact_code($contact , $field_name , $lang );
    $selected = active_chatbox_info($sub , $lang )[0] ;
    return bot_answer_msg( $selected ) ;
  }

  // All comming is a real session which not ==  ''  or expired 
  // so depennd on it's msg['body'] we will take the suitable actions 
  $body = $msg['body'] ;
  $selected = active_chatbox_info($sub , $lang )[0] ;

  // Languages
  $langs =  array('ar','en','fr');
  foreach ($langs as $language) {
    if( $selected[$language.'_checkbox'] ==  true && $body == $selected[$language.'_slug']  ){
      update_last_interact_code($contact , $field_name , $language );
  
      if($body != $language ){ // if deferant language : get the new language welcome message
        $selected = active_chatbox_info($sub , $language )[0] ;
      }
      return bot_answer_msg( $selected ) ;
    }  
  }

  $error_msg  = $selected['error'] ; 
  // Navigations
  if((int)$body >  0 ){ //  Have a real code to go forward one step
    $original_selected =  $selected ;
    $body =  (int)$body -  1 ;
    unset($explode[0]);
    unset($explode[1]);
    $explode[] = $body;
    $screen = array();
    $path =  '';
    foreach ($explode as $index => $key) {
      $seperator =  $index > 2 ?  "_" : '';
      $path = $path.$seperator.$key ;
      $selected =  isset($selected['childs'][$key]) ? $selected['childs'][$key] :  false;
    }
    // check for avilability 
    if ($selected !=  false ){
      return update_last_interact_code($contact , $field_name , $lang , $path);
      return bot_answer_msg( $selected) ;  
    }else {
      // update_last_interact_code($contact , $field_name , $lang , $original_path);
      return bot_answer_msg( $original_selected , $error_msg ) ;
    }
  }

  if($body == $selected['back_home'] )  { // back home
    update_last_interact_code($contact , $field_name , $lang );
    return bot_answer_msg( $selected ) ;
  }

  if($body == $selected['one_step_back'] )  { // back one step

    unset($explode[0]);
    unset($explode[1]);
    $counter =  count($explode);
    $path =  '';
    foreach ($explode as $index => $key) {
      $counter -- ;
      if($counter > 0 ){
        $seperator =  $index > 2 ?  "_" : '';
        $path = $path.$seperator.$key ;
        $selected =  $selected['childs'][$key];  
      }
    }
    
    update_last_interact_code($contact , $field_name , $lang ,  $path  );
    return bot_answer_msg( $selected ) ;
  }








}

/*


  
  $contact            = get_contact_by_phone($phone , $client_name);
  $last_interact_code = get_post_meta( $contact, last_interact_code_name($sub) , true );
  $explode = explode('_',$last_interact_code);





  $phone        = pure_phone($msg['author']);
  $client_name  = $msg['senderName'] ; 
  $last_code    = last_interact_code($sub,$phone,$client_name);
  $exploded_code= explode('_' , $last_code );
  $time         = $exploded_code[0];
  $lang         = $exploded_code[1];
  $chatbox      = active_chatbox($sub) ; 
  $return       = carbon_get_post_meta($chatbox, $lang );

  // Normal Codes

  if($exploded_code[2] == '0' ){ // it's first time
    
    return  bot_answer_msg($return[0]);
  }


  if((int)$msg['body']  >  0 ){

  }else{
    
  }








  // Change Language
  if($msg['body'] == $back_home  ){

  }else{
    $back_home = carbon_get_post_meta($chatbox, 'back_home' );  
  }




  // Navigation
  if($msg['body'] == $back_home ){

  }else{
    $one_step_back  = carbon_get_post_meta($chatbox, 'one_step_back');
  }

  if($msg['body'] == $one_step_back ){

  }else{
    return $return[0]['error'] ;
  }

  if($msg['body'] == $one_step_back ){

  }else{
    return $return[0]['error'] ;
  }


  */