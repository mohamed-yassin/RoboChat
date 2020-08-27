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

            if($msg->type == 'image'){
                $prepared_msgs[$chat_id]['last_msg'] = '<i id="00447449492715_arrow_class" class="fas fa-image "></i> ';
                $prepared_msgs[$chat_id]['last_msg'] .=  property_exists($msg , 'caption') && $msg->caption != "" ?  $msg->caption :  "image" ;
            }else {
                $prepared_msgs[$chat_id]['last_msg'] =  $msg->body ;
            }
            $prepared_msgs[$chat_id]['original_last_msg_time'] = $msg->time;
            $prepared_msgs[$chat_id]['last_msg_time'] = msg_time($msg->time);
            $prepared_msgs[$chat_id]['last_msg_direction'] =  $msg->fromMe == 1 ?  1 : 0 ; // out==1 && in == 0
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
       $files       = explode('&&&&&', $files) ;
       $msg_body    =  wp_get_attachment_url($files[0]);
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

    $clients = array_unique((array)$clients);

    if(is_array($clients)  &&  count($clients) >  0){
        foreach ($clients as $client) {
            $phone =  get_post_meta( $client , 'phone', true );
            $phone =  pure_phone($phone); 
            $msg_body  =  $type == 'chat'  ? translate_short_codes($msg_body ,$phone) : $msg_body ;

            $data = array(
                'mobileNumber' => $phone,
                'msg_body' => $msg_body,
                'msg_type' => $type,
                'msg_caption' => translate_short_codes($caption,$phone)  ,
                'isSent' => '0',
                'note' => ' ',
                'source' => '1',
                'status' => '1',
                'createdAt' => date('Y-m-d h:i:s'),
                'updatedAt' => date('Y-m-d h:i:s'),
            );
        };
    }else {
        $_SESSION['bulk_msg_error'] =  ' ------ لم يتم اضافه اي عملاء ------ ';
    }
    //wp_redirect( $_REQUEST['redirect'] );
    exit;
};
function get_emojis(){
    $emojis['smiles'] =  '😀 😃 😄 😁 😆 😅 😂 🤣 ☺️ 😊 😇 🙂 🙃 😉 😌 😍 🥰 😘 😗 😙 😚 😋 😛 😝 😜 🤪 🤨 🧐 🤓 😎 🤩 🥳 😏 😒 😞 😔 😟 😕 🙁 ☹️ 😣 😖 😫 😩 🥺 😢 😭 😤 😠 😡 🤬 🤯 😳 🥵 🥶 😱 😨 😰 😥 😓 🤗 🤔 🤭 🤫 🤥 😶 😐 😑 😬 🙄 😯 😦 😧 😮 😲 🥱 😴 🤤 😪 😵 🤐 🥴 🤢 🤮 🤧 😷 🤒 🤕 🤑 🤠 😈 👿 👹 👺 🤡 💩 👻 💀 ☠️ 👽 👾 🤖 🎃 😺 😸 😹 😻 😼 😽 🙀 😿 😾';
    return $emojis ;
}

function send_unsent_queried_msgs($sub) {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
    $table_name = get_table_name($sub,'msgs');
    $unsent_msgs = $wpdb->get_results( "select * from  $table_name  where isSent= 0  limit 1 " );
    foreach ($unsent_msgs as $key => $msg) {
        $parametars['phone'] =  $msg->mobileNumber ; 
        if($msg->msg_type == 'file'){
            $parametars['body']     = production !== true ? 'https://cdn.pixabay.com/user/2014/05/07/00-10-34-2_250x250.jpg' : $msg->msg_body ;
            $parametars['caption']  = $msg->msg_caption;
            $parametars['filename'] = 'image';
        }else {
            $parametars['body']     = $msg->msg_body;
        }
        $response =  whatsapp_send_messege($sub,$parametars, $msg->msg_type);
        if($response['status']  == 1 ){
            $data  = array('isSent' => '1');
            $where = array('id' => $msg->id);
            $wpdb->update( $table_name, $data, $where ); // Also works in this case.    
        }else {
            $data  = array('note' =>  array_to_text($response));
            $where = array('id' => $msg->id);
            $wpdb->update( $table_name, $data, $where ); // Also works in this case.    
        }
    }
    return $myrows ;
}
function robo_chat_text_area($value){ 
    $emojis = get_emojis();
    $codes  = get_available_codes();

    
    ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
	integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">

    
    <div class="justify-self-end align-items-center flex-row d-flex" id="input-area">
		<input id="sub" name="sub" type="hidden" value="<?=  get_page_sub_id() ?>" >
		<textarea required name="content" id="input" placeholder="اكتب رسالة" class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm send-bulk-msg-area auto-aligning"><?= $value ?></textarea>
		<div class="nav-item dropdown ml-auto">
			<div class="">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
					<i id="msg_sending_btn" class="fas fa-ellipsis-v text-muted px-3" style="cursor:pointer;"> اكواد جاهزه </i>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<?php
						foreach ($codes as $name => $code) {
							echo '<a class="dropdown-item data-content" data-content="{{'.$code.'}}" >'.$name.'</a>';
						}
					?>
				</div>
			</div>
			<div class="">
				<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
					aria-haspopup="true" aria-expanded="false">
					<i id="msg_sending_btn" class="fas fa-ellipsis-v text-muted px-3" style="cursor:pointer;">
						وجود تعبيريه
					</i>
				</a>
				<div class="dropdown-menu dropdown-menu-right">
					<?php
						foreach ($emojis as $group_name => $group) {
							$group =  explode(' ' , $group);
							foreach ($group as $emoji) {
								echo '<span class="data-content" data-content="'.$emoji.'" >'.$emoji.'</span>';
							}
						}
					?>
				</div>
			</div>
		</div>
	</div>
    
    <script>
        jQuery(".data-content").click(function(e){
            if(document.getElementById("input").disabled == false ){ // open
                document.getElementById('input').value = document.getElementById('input').value + " " +  this.getAttribute('data-content') ;
            }
        });
    </script>


<?php }
function get_available_codes(){
    $codes =  array(
        'المستخدم الحالي' =>  'current_user',
        'رقم هاتف العميل' =>  'phone',
        'اسم الاول للعميل' =>  'first_name',
    );
    return  $codes ;
}