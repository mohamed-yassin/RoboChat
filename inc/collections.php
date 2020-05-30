<?php
// Redirect not login user to login page 
//add_action( 'template_redirect', 'checkout_redirect_non_logged_to_login_access');

// Displaying a message on cart page for non logged users (Optional)
//add_action( 'woocommerce_before_cart', 'customer_redirected_displaying_message');


foreach( $blog_ids as $blog_id ){
    switch_to_blog( $blog_id );
    //Do stuff
    restore_current_blog();
}

$blogs =  wp_get_sites();
