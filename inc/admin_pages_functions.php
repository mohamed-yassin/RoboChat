<?php 
function current_sub_page_url($extention = NULL)
{
    return admin_url('admin.php?page='.$_GET['page'].$extention);
}
function get_page_sub_id()
{
    if(isset($_GET['page'])){
        $sub = $_GET['page'];
        $sub = explode('_', $sub);
        $sub = $sub[1];    
    }else {
        $sub =  0;
    }
    
    return  $sub;
}
function get_clients(){
    $args =  array(
        'post_type' =>'client',
    );
    $clients =  (array)get_posts($args);
    foreach ($clients as $key => $client) {
        $clients[$key]->phone = get_field('phone',$client->ID);
    }
    return $clients;
}
function get_templates(){
    $args =  array(
        'post_type' =>'template',
    ); 
    return (array)get_posts($args);
}
function message_time($time){
    return  $time ;
}
function message_reciever_number($chatid){
    $number  =  explode ('@' , $chatid);
    $number  =  pure_phone ($number[0]);
    return $number ;
}

function is_group($chatId){
    $id   =  explode ('@' , $chatId);
    $type =  explode ('.' , $id[1])[0];

    return $type == 'g' ?  true :  false ;
}
function contact_info($num){
    $num =  pure_phone($num);
    $args = array(
        'post_type'         => 'client',
        'posts_per_page'    => 1,
        'meta_query'        => array(
            array(
                'key'     => 'phone',
                'value'   => $num ,
                'compare' => '=',
            ),
        ),
    );
    $contacts =  get_posts($args);
    if(is_array($contacts) && count($contacts) >  0){
        $return['name']       = $contacts[0]->post_title  ;
        $return['img']        = get_the_post_thumbnail_url($contacts[0]->ID ) ;
        $return['phone']      = $num ;
        $return['first_name'] = get_post_meta( $contacts[0]->ID, 'first_name', true );
    }else {
        $return = FALSE;
    }
    return $return  ;
}
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
function pure_phone($num){
    return  "00".(int) preg_replace('/\D/', '', $num); 
}
function filter_phone_number( $value, $post_id, $field ) {
    return pure_phone($value);  
}
function msg_time($time){
    $time =  date("H:i d/m",$time) ;
    return  $time; 
}
function  msg_day($time){
    return date("d/m",$time) ;
}
function has_access_to_this_sub(){
    $sub  = $_GET['sub'];
    $subs = subs_option_field_array();
    return in_array($sub,$subs) ?  true :  false ; 
}