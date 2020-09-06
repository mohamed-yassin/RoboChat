<?php
function internal_api_talk_to_bot( $data ) {
  $slug =  $data['sub'];
  $sub = related_sub_id($slug);
  if($sub >  0){
    if(isset($data['messages']) || production == true){
      $msgs = $data['messages'];
      if(is_array($msgs) &&  count($msgs) >  0){
        $options = get_option('roboChat_settings');
        $chat_box =  $options['sub_'.$slug.'_chatbox'];
        if($chat_box  >  0){
          foreach ($msgs as $key => $msg) {
            if( $msg['fromMe'] != 1 && $msg['fromMe'] != 'true' ){
              $bot_answer           = bot_answer($slug, $msg);
              $parametars['phone']  = pure_phone($msg['author']) ;
              $parametars['body']   = $bot_answer['type'] == 'chat' ? $bot_answer['body'] : file_url($bot_answer['body']) ;
              $parametars['caption']= $bot_answer['caption'];
              $parametars['filename']='chat-image.jpg';
              
            //return $sub ; 
              
              //return pre($parametars) ;
              return whatsapp_send_messege($sub,$parametars,$bot_answer['type']);
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
  return time()."_".$lang."_0"; 
}
function active_chatbox($slug){
  $options = get_option('roboChat_settings');
  $value = $options['sub_'.$slug.'_chatbox'] ;
  return $value >  0  ? $value : 0 ;
}
function  active_chatbox_info($slug , $info ){ // @@@
  $chatbox =  active_chatbox($slug) ; 
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
function bot_answer_msg($selected ,  $msg_error = '' ){
  $type     = 'chat';
  $caption  = '';

  if($msg_error !=  '' ){
    $body =  $msg_error;
  }else {
    $new_line =  "\n\n";
    $dash_line = '------------------------------------------------------';
    $childs =  "";
    if(is_array($selected) && isset($selected['childs'])){
      foreach ($selected['childs'] as $key => $child) {
        $childs .= "*".($key+1)."*" ." - " . $child['header'] . $new_line ;
      }  
    }
    $content =  $selected['header'].$new_line.$selected['body'].$new_line.$childs.$new_line.$dash_line.$new_line.$selected['footer'];

    if($selected['image'] !=  ''){
      $type     = 'file';
      $body     = $selected['image'];
      $caption  = $content ;
    }else {
      $body  = $content;
    }
  }
  
  return array(
    'type'    => $type,
    'body'    => $body,
    'caption' => $caption,
  ); 
}
function update_last_interact_code($contact , $field_name ,$lang =  'df' , $path  = ''){
  $last_interact_code = time()."_".$lang; 
  $last_interact_code = $path != '' ? $last_interact_code."_".$path :  $last_interact_code ; 
  update_post_meta( $contact , $field_name  , $last_interact_code );
  return $last_interact_code ;
}

function bot_answer($slug,$msg){ // @@
  $phone        = pure_phone($msg['author']);
  $client_name  = $msg['senderName'] ; 
  $contact      = get_contact_by_phone($phone , $client_name);
  $field_name   = last_interact_code_name($slug);
  $chatbox      = active_chatbox($slug); 

  $last_interact_code = get_post_meta( $contact, $field_name , true );

  if($last_interact_code ==  ''){ // if no session
    $df_lang = active_chatbox_info($slug , 'defult_language');
    update_last_interact_code($contact , $field_name , $df_lang );

    $selected = active_chatbox_info($slug , $df_lang )[0] ;
    return bot_answer_msg( $selected ) ;
  }


  $explode    = explode('_', $last_interact_code );
  $time       = (int)$explode[0];
  $lang       = $explode[1];
  $now        = time();
  $def        = $now - $time ;
  $session    = active_chatbox_info($slug , 'session_duration' );

  if($def >  $session){ // Expired Session 
    update_last_interact_code($contact , $field_name , $lang );
    $selected = active_chatbox_info($slug , $lang )[0] ;
    return bot_answer_msg( $selected ) ;
  }

  // All comming is a real session which not ==  ''  or expired 
  // so depennd on it's msg['body'] we will take the suitable actions 
  $body = $msg['body'] ;
  $selected = active_chatbox_info($slug , $lang )[0] ;

  // Languages
  $langs =  array('ar','en','fr');
  // their is a bug where the user use the same ar,en,fr 
  foreach ($langs as $language) {
    if( $selected[$language.'_checkbox'] ==  true && strtolower($body) == strtolower($selected[$language.'_slug'])  ){
      update_last_interact_code($contact , $field_name , $language );

      $selected = active_chatbox_info($slug , $language )[0] ;
      return bot_answer_msg( $selected ) ;
    }  
  }
  $error_msg  = $selected['error'] ;
  // Navigations
  if((int)$body >  0 ){ //  Have a real code to go forward one step
    $selected_2 =  $selected ;
    $body =  (int)$body -  1 ;
    
    unset($explode[0]); // time
    unset($explode[1]); // language

    $explode[] = $body;
    $screen = array();
    $path =  '';
    foreach ($explode as $index => $key) {
      $seperator  =  $index > 2 ?  "_" : '';   // 0&1 were unset , 2 is the first item so we don't need the seperator before it 
      $path       = $path.$seperator.$key ;
      $selected_2       =  isset($selected_2['childs'][$key]) ? $selected_2['childs'][$key] :  false;
    }
    // check for avilability 
    if ($selected_2 !=  false ){
      update_last_interact_code($contact , $field_name , $lang , $path);
      return bot_answer_msg( $selected_2) ;  
    }
  }

  if($body == $selected['back_home'] )  { // back home
    update_last_interact_code($contact , $field_name , $lang );
    return bot_answer_msg( $selected ) ;
  }

  if($body == $selected['one_step_back'] )  { // back one step

    unset($explode[0]); // time
    unset($explode[1]); // language
    
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

  // if nothing :  it's error code
  return bot_answer_msg( $selected , $error_msg );
}