<?php 

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

    
    wp_redirect( $_REQUEST['redirect'] );
    exit;
};
function get_emojis(){
    $emojis['smiles'] =  '😀 😃 😄 😁 😆 😅 😂 🤣 ☺️ 😊 😇 🙂 🙃 😉 😌 😍 🥰 😘 😗 😙 😚 😋 😛 😝 😜 🤪 🤨 🧐 🤓 😎 🤩 🥳 😏 😒 😞 😔 😟 😕 🙁 ☹️ 😣 😖 😫 😩 🥺 😢 😭 😤 😠 😡 🤬 🤯 😳 🥵 🥶 😱 😨 😰 😥 😓 🤗 🤔 🤭 🤫 🤥 😶 😐 😑 😬 🙄 😯 😦 😧 😮 😲 🥱 😴 🤤 😪 😵 🤐 🥴 🤢 🤮 🤧 😷 🤒 🤕 🤑 🤠 😈 👿 👹 👺 🤡 💩 👻 💀 ☠️ 👽 👾 🤖 🎃 😺 😸 😹 😻 😼 😽 🙀 😿 😾';
    return $emojis ;
}
