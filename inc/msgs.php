<?php 
function prepare_msgs($msgs){
    $sessions =  available_sessions(get_page_sub_id());
    foreach ($msgs as $key => $msg) {
        if(!is_group($msg->chatId)){
            $chat_id = pure_phone($msg->chatId);  
            $msg->real_time = date("H:i",$msg->time);

            $contact_info = contact_info(message_reciever_number($msg->chatId)) ;
            $prepared_msgs[$chat_id]['name'] =  isset($contact_info['name']) ? $contact_info['name'] :  $msg->chatName ;
            $prepared_msgs[$chat_id]['img']  =  isset($contact_info['img']) && $contact_info['img'] != '' ? $contact_info['img']   :  dflt_user_img ; 

            $prepared_msgs[$chat_id]['available']  =  isset($sessions[$chat_id]['available']) ? $sessions[$chat_id]['available'] : 1 ;                    
            $prepared_msgs[$chat_id]['available_icon']      =  isset($sessions[$chat_id]['available_icon']) ?  $sessions[$chat_id]['available_icon'] :  '';                    
            $prepared_msgs[$chat_id]['last_msg'] =   isset($prepared_msgs[$chat_id]['last_msg']) && $prepared_msgs[$chat_id]['last_msg'] >   $msg->time  ? $prepared_msgs[$chat_id]['last_msg'] :  $msg->body   ;
            if(! isset($prepared_msgs[$chat_id]['last_msg']) ||  $prepared_msgs[$chat_id]['last_msg'] <   $msg->time  ){
                if($msg->type == 'image'){
                    $prepared_msgs[$chat_id]['last_msg'] = '<i id="00447449492715_arrow_class" class="fas fa-image "></i> ';
                    $prepared_msgs[$chat_id]['last_msg'] .=  property_exists($msg , 'caption') && $msg->caption != "" ?  $msg->caption :  "image" ;

                }else {
                    $prepared_msgs[$chat_id]['last_msg'] =  $msg->body ;
                }
                $prepared_msgs[$chat_id]['original_last_msg_time'] = $msg->time;
                $prepared_msgs[$chat_id]['last_msg_time'] = msg_time($msg->time);
                $prepared_msgs[$chat_id]['last_msg_direction'] =  $msg->fromMe ;  // from me 1 else 0 or impty
            }
            $prepared_msgs[$chat_id]['msgs'][$msg->id] =  $msg ;
        }
    }
    
    array_multisort(array_column($prepared_msgs, 'original_last_msg_time'), SORT_DESC, $prepared_msgs);
    return $prepared_msgs  ;
}
function translate_short_codes($msg , $contact = FALSE  ){
    $contact_info =  contact_info(pure_phone($contact)) ; 
    if($contact != FALSE  &&  $contact_info != FALSE){
        $searchfor[] = '{{first_name}}';
        $replacing[] = $contact_info['first_name'];

        $searchfor[] = '{{phone}}';
        $replacing[] = $contact_info['phone'];
    }

    // General
    $searchfor[] = '{{current_user}}';
    $replacing[] = not_null_auther_name(get_current_user_id());

    $searchfor[] = '{{rand}}';
    $replacing[] = "random content  " . rand(100,1000). " " . date('H:i:s');

    return str_replace($searchfor,$replacing,$msg);   
};
function restore_new_line($msg){
    return str_replace("\n\n","</br>",$msg);   
};
function compose_messages_handler() {
    global $wpdb;
    $msg_body        = $_REQUEST['msg'];
    $clients         = $_REQUEST['clients'];
    $lists           = $_REQUEST['lists'];
    $table           = $_REQUEST['table'];
    $files           = $_REQUEST['files'];
    $user            = get_current_user_id();
    $caption         = "";
    $type            = "chat";
    $current_wpnonce = $_REQUEST['current_wpnonce'];

    if(strlen($files) >  0){ 
       $caption     = $msg_body ; 
       $msg_body    = $files ; 
       $type        = "file";
    }


    $args =  array(
        'post_type' => 'client',
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'list',
                'field'    => 'slug',
                'terms'    => $lists,
            )
        )
    );


    $lists_clients=get_posts($args);

    foreach ($lists_clients as $client) {
        $clients[]  = $client->ID ;     ; 
    }

    $clients = array_unique($clients);


    if(is_array($clients)  &&  count($clients) >  0){
        foreach ($clients as $client) {
            $phone =  get_post_meta( $client , 'phone', true );
            $phone =  pure_phone($phone); 
            $msg_body  =  translate_short_codes($msg_body ,$phone);

            $data = array(
                'msg_body' => $msg_body,
                'type' => $table,
                'msg_caption' =>$caption, 
                'mobileNumber' => $phone,
                'isSent' => '1',
                'note' => ' ',
                'source' => '1',
                'status' => '1',
                'updatedAt' => '0000-00-00 00:00:00',
            );
            $wpdb->insert($table,$data);
        };
    }else {
        $_SESSION['bulk_msg_error'] =  ' ------ لم يتم اضافه اي عملاء ------ ';
    }
    wp_redirect( $_REQUEST['redirect'] );
    exit;
};
function get_emojis(){
    $emojis['smiles'] =  '😀 😃 😄 😁 😆 😅 😂 🤣 ☺️ 😊 😇 🙂 🙃 😉 😌 😍 🥰 😘 😗 😙 😚 😋 😛 😝 😜 🤪 🤨 🧐 🤓 😎 🤩 🥳 😏 😒 😞 😔 😟 😕 🙁 ☹️ 😣 😖 😫 😩 🥺 😢 😭 😤 😠 😡 🤬 🤯 😳 🥵 🥶 😱 😨 😰 😥 😓 🤗 🤔 🤭 🤫 🤥 😶 😐 😑 😬 🙄 😯 😦 😧 😮 😲 🥱 😴 🤤 😪 😵 🤐 🥴 🤢 🤮 🤧 😷 🤒 🤕 🤑 🤠 😈 👿 👹 👺 🤡 💩 👻 💀 ☠️ 👽 👾 🤖 🎃 😺 😸 😹 😻 😼 😽 🙀 😿 😾';
    return $emojis ;
}
