<?php

// Handle the subscription depend on the client selections
add_action( 'woocommerce_subscription_payment_complete', 'subscription_handler' );

// Add a select field of the user blogs to the woocommerce page
add_action('woocommerce_after_order_notes', 'user_dashboards_option_field');

// Redirect not login user to login page 
//add_action( 'template_redirect', 'checkout_redirect_non_logged_to_login_access');

// Displaying a message on cart page for non logged users (Optional)
//add_action( 'woocommerce_before_cart', 'customer_redirected_displaying_message');

// Add clients dasboard
add_action( 'init', 'cpt_clients' );

// admin pages 
add_action( 'admin_menu', 'robo_add_admin_menu' );
//add_action( 'admin_menu', 'whatsappapi_add_admin_menu' );

add_action( 'admin_menu', 'client_dashboard' );

// compose a new message
add_action( 'admin_post_compose_messages', 'compose_messages_handler' );