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
        $clients[$key]->phone = get_post_meta( $client->ID , 'phone', true ) ;
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
function get_contact_by_phone($phone ,$new_contact_name =  false , $return =  'id' ,$lists = array(), $fields = array() ){
    $phone =  pure_phone($phone);
    $args = array(
        'post_type'         => 'client',
        'posts_per_page'    => 1,
        'meta_query'        => array(
            array(
                'key'     => 'phone',
                'value'   => $phone ,
                'compare' => '=',
            ),
        ),
    );
    $contacts = get_posts($args);
    if( isset($contacts[0]) ){
        if($return == 'id'){
            return $contacts[0]->ID;
        }else {
            return $contacts[0];
        }
        return  $contacts[0] ; 
    }elseif($new_contact_name !=  false ){
        $my_client = array(
            'post_title'    => wp_strip_all_tags( $new_contact_name  ),
            'post_type'     => 'client',
            'post_status'   => 'publish',
            'post_author'   => 1,
          );
        $id= wp_insert_post( $my_client );
        update_post_meta( $id , 'phone', $phone );
        if(count($fields) >  0 ){
            foreach ($fields as $key => $value) {
                update_post_meta( $id , $key , $value  );
            }
        }
        if(count($lists) >  0 ){
            foreach ($lists as $list_id) {
                if($list_id !=  '' ){
                    wp_set_object_terms( $id , $list_id , 'list');
                }
            }
        }
        if($return == 'id'){
            return $id;
        }else {
            return get_post($id);
        }
    }
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
function edit_wordpress_dashboard_footer () 
{
    //echo '<span id="footer-thankyou">Developed by <a href="http://www.designerswebsite.com" target="_blank">Your Name</a></span>';
}