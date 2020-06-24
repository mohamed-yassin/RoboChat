<?php
function subscription_handler($sub){
    $blog =  isset( $_REQUEST['dashboards_option_field'] ) ? $_REQUEST['dashboards_option_field']  : 0  ;
    $user = get_current_user_id();

    if($blog  ==  0){
        // make blog for the client 
        $title   = " :: MY RoboChat Sub" ;
        $path    = "sub_" . $sub->ID; 
        $options = array();
        $blog    = wpmu_create_blog( domain , $path, $title, $user , $options ,  1);
    }
    // add the sub to the realated blog
    add_sub_to_blog($blog,$sub->ID);

    // make new dp_tables
    create_custom_msgs_table($sub->ID);

    // update data of the supscription
    update_field('web_hock',get_open_hock(),  $sub->ID);        
    
    // save data of the proceess in posts for testing
    test_post($dashboard);
}

function user_dashboards_option_field($checkout)

{
    $blogs  =      $user_blogs = get_blogs_of_user(get_current_user_id());

    if(isset($blogs[1])){
        unset($blogs[1]); // remove the master website , if the client have any aceess to it ;
    }

    if(count($blogs) >  0){
        foreach ($blogs as $key => $blog) {
            $blogs_array[$blog->userblog_id] =  "#".$blog->userblog_id .  " : " . $blog->blogname;
        }
        $blogs_array[0] =  "No , Add to a new dashboard" ;
    
    }else {
        $blogs_array[0] =  "I don't have , Create a new one for me" ;

    }
    echo '<div id="dashboards_option_field"><h4>' . __('Add this subscription to old dashboard') . '</h4>';
    woocommerce_form_field(
        'dashboards_option_field', 
        array(
            'type' => 'select',
            'class' => array(
            'my-field-class form-row-wide'
        ) ,
        'label' => __('Custom Additional Field') ,
        'options' =>  $blogs_array ,
    ) ,
    $checkout->get_value('dashboards_option_field')
);
    echo '</div>';
}

function checkout_redirect_non_logged_to_login_access() {

    // Here the conditions (woocommerce checkout page and unlogged user)
    if( !is_user_logged_in()){

        // Redirecting to your custom login area
        wp_redirect( get_permalink( get_option('woocommerce_myaccount_page_id') ) );

        // always use exit after wp_redirect() function.
        exit;
    }
}

function customer_redirected_displaying_message() {
    if( !is_user_logged_in() ){
        // HERE Type your displayed message and text button
        $message = __('To access checkout, you need first to be logged in', 'woocommerce');
        $button_text = __('Login area', 'woocommerce');

        $cart_link = get_permalink( get_option('woocommerce_myaccount_page_id') );

        wc_add_notice(  $message . '<a href="' . $cart_link . '" class="button wc-forward">' . $button_text . '</a>', 'notice' );
    }
}
function sub_connection_data($sub){
    switch_to_blog(1); // go to the master
    $sub_connection_data['instant']= get_post_meta( $sub ,'instant_name'  , true) ;
    $sub_connection_data['api']= get_post_meta( $sub ,'api'  , true) ;
    $sub_connection_data['token']= get_post_meta( $sub ,'token'  , true) ;
    $sub_connection_data['msgs'] = get_post_meta( $sub ,'available_daily_msgs'  , true) ;
    restore_current_blog(); // back to the current
    return $sub_connection_data;
}
function reduce_msgs_counter($sub,$reduce = 1 ){
    switch_to_blog(1);
    $msgs_balance = (int)(get_post_meta( $sub ,'available_daily_msgs'  , true)) ;
    $msgs_balance = ($msgs_balance -  $reduce) ;
    update_post_meta( $sub ,'available_daily_msgs'  , $msgs_balance );
    restore_current_blog(); 
    return $msgs_balance;
}
function get_open_hock(){
    $hock  = rand(10000,15000);
    return $hock ;
};
function sub_errors_log($sub, $error){
    switch_to_blog(1);
    $msgs_balance = get_post_meta( $sub ,'errors_log'  , true);
    $msgs_balance =  json_decode($msgs_balance , true );
    $msgs_balance[date('Y/m/d H:i:s')]= $error ;
    $msgs_balance = json_encode($msgs_balance);
    update_post_meta( $sub ,'errors_log',$msgs_balance);
    restore_current_blog(); 
}
function robo_nonce(){
    return wp_create_nonce(subs_option_field_value());
}
function verify_robo_nonce($action = NULL){
    if($action == NULL){
        $action = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] :  robo_nonce();
    }
    wp_verify_nonce($action,robo_nonce());
}