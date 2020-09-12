<?php

function sub_url_slug($sub =  1){
    if( !($sub > 0) ){
        return ; 
    }
    $max =  1000000000 ;  // 10 letters
    $new_sub =  $max -  $sub ;
    $len_diff =  strlen($max) - strlen($new_sub)  ;

    for ($i=0; $i < $len_diff ; $i++) { 
        $new_sub =  '0'.$new_sub;
    }

    $new_sub =  str_split($new_sub);
    $part_2 =  number_format($new_sub[2].$new_sub[3], 0);
    $part_3 =  number_format($new_sub[4].$new_sub[5], 0);
    $part_4 =  number_format($new_sub[6].$new_sub[7], 0);
    $part_5 =  number_format($new_sub[8].$new_sub[9], 0);
    
    // deal with the part_1 with will not only be just a part , but will be the key for the encription and decription 
    if($new_sub[0] == $new_sub[1]){
        $part_1_str = $new_sub[0];
    }else {
        $shuffled_capital   = array('W','K','H','R','L','Q','I','P','G','V','D','Z','U','B','C','F','N','Y','X','M','O','J','E','A','T','S');
        $shuffled_small     = array('f','s','r','t','z','w','m','j','e','c','x','g','l','b','p','k','d','h','n','v','q','i','u','y','o','a');
        $shuffled_small     = number_format($new_sub[0].$new_sub[1] ,0);
        $part_1_str         = $all_1[($part_1-1+60)];    
    }
    echo  $part_1_str ;
    
    return ;

    $sub = urlencode( base64_encode ($sub));
    $capitals   = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'); // 26
    $smalls     = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'); // 26  
    //$numbers    = array('0','1','2','3','4','5','6','7','8','9','9' ,'9'); // 10
    //$chars      = array('-', '.', '_', '~'); // 4  the allowed safe chars can bwe used in urls

    $all =  array_merge($capitals, $smalls , $numbers);  
    $all =  array_unique($all);
    shuffle($all);

    $text =  '';
    foreach ($all as $char) {
        $text .= "'$char',";
    }

    // odd  & odd 
    // odd  & even

    // even & even
    // even & odd
    
    // odd & 0
    // even & 0
    // 0 & 0
    // even & 0
    // odd & 0
    
    $all_2 = array('Q','p','K','w','0','q','4','B','L','n','D','C','y','z','d','V','c','j','v','b','O','R','g','G','x','M','7','A','u','a','h','T','E','3','9','F','1','8','s','N','f','W','X','o','P','l','m','t','I','H','e','5','J','Y','S','Z','k','2','U','6','r','i');
    $all_3 = array('O','g','2','5','l','w','n','H','F','N','K','S','a','d','v','y','W','I','k','1','e','j','A','b','3','9','p','Q','M','i','J','r','B','m','t','4','Y','G','z','D','8','U','s','6','0','R','T','L','o','P','u','h','X','q','E','7','Z','C','c','x','V','f');
    $all_4 = array('e','R','h','0','J','7','q','m','N','z','x','v','Y','S','s','f','n','T','D','a','u','P','E','i','9','W','6','K','A','U','Z','5','g','j','Q','c','H','X','t','k','3','y','l','C','B','d','b','8','M','4','O','L','F','r','I','o','p','G','1','w','2','V');
    $all_5 = array('C','d','N','2','D','J','H','9','q','I','V','o','s','Q','l','8','6','x','7','M','r','g','1','z','Y','a','n','m','p','3','f','F','R','4','v','U','i','X','B','P','E','T','t','b','A','G','u','c','5','O','L','S','k','j','0','K','y','w','h','Z','W','e');
    $all_6 = array('n','h','m','9','X','5','u','f','y','c','2','v','j','K','8','D','A','7','s','W','E','3','S','i','q','g','C','G','N','o','x','4','6','J','U','T','L','p','F','0','R','I','M','Y','B','Z','b','l','t','z','r','H','d','O','a','e','k','P','1','w','Q','V');
    $all_7 = array('2','o','v','s','X','d','R','C','i','8','e','0','z','U','I','9','y','Q','J','F','c','D','M','K','7','4','5','f','m','E','S','P','T','H','3','L','G','b','N','w','Z','A','t','g','q','a','p','k','B','x','u','V','W','l','Y','1','r','n','j','h','6','O');
    $all_8 = array('e','X','J','v','a','9','u','8','w','N','6','r','t','G','7','0','A','p','V','E','x','W','T','l','o','n','h','H','O','z','s','j','3','F','1','m','2','g','B','Q','k','i','d','I','R','K','S','P','Y','f','c','C','L','q','D','5','y','b','Z','4','U','M');
    $all_9 = array('y','Y','8','w','l','n','p','b','T','2','E','D','F','7','G','I','s','q','z','6','a','P','m','M','L','g','1','X','O','W','Z','B','v','V','r','k','h','u','j','Q','J','A','C','e','i','x','c','R','N','t','S','H','5','4','U','0','K','o','d','9','f','3');
    $all_10= array('0','C','9','B','c','E','G','A','i','Z','q','v','O','x','S','a','L','8','W','n','X','d','g','Q','6','s','7','l','P','T','N','k','K','M','f','z','F','Y','o','2','e','U','p','R','D','u','4','j','m','I','5','b','V','y','w','t','1','r','3','h','H','J');

    return $sub ;  
}
function current_sub_page_url($extention = NULL)
{
    return admin_url('admin.php?page='.$_GET['page'].$extention);
}
function get_page_sub_id()
{
    
    if(isset($_GET['page'])){
        $slug = $_GET['page'];
        $slug = explode('_', $slug);
        $slug = $slug[1];  
        $sub =  related_sub_id($slug); 
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