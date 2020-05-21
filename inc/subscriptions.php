<?php
function subscription_handler($sub){
    $blog =  isset( $_REQUEST['dashboards_option_field'] ) ? $_REQUEST['dashboards_option_field']  : 0  ;
    $user = get_current_user_id();

    if($blog  ==  0){
        // make blog for the client 
        $title   = "#".$sub->ID ;
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