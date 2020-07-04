<?php

// Handle the subscription depend on the client selections
add_action( 'woocommerce_subscription_payment_complete', 'subscription_handler' );

// Add a select field of the user blogs to the woocommerce page
add_action('woocommerce_after_order_notes', 'user_dashboards_option_field');

// Redirect not login user to login page 
//add_action( 'template_redirect', 'checkout_redirect_non_logged_to_login_access');

// Displaying a message on cart page for non logged users (Optional)
//add_action( 'woocommerce_before_cart', 'customer_redirected_displaying_message');

// Custom Posts
add_action( 'init', 'cpt_clients' );
add_action( 'init', 'cpt_templates' );

// Custom Taxonomies
add_action( 'init', 'cptui_register_my_taxes_template_category' );
add_action( 'init', 'cptui_register_my_taxes_list' );


// admin pages 
add_action( 'admin_menu', 'robo_add_admin_menu' );
//add_action( 'admin_menu', 'whatsappapi_add_admin_menu' );

add_action( 'admin_menu', 'client_dashboard' );

// HTTP Custom Requests Handler
add_action( 'admin_post_compose_messages', 'compose_messages_handler' );

// filter the saved phone number
//add_filter('acf/update_value', 'filter_phone_number', 10, 3);

// send message with ajax
add_action( 'wp_ajax_send_instant_msg_action', 'send_instant_msg' );
add_action( 'wp_ajax_update_session_action', 'update_session' );
add_action( 'wp_ajax_update_data_action', 'update_data' );
add_action( 'wp_ajax_chat_api_main_processes_action', 'chat_api_main_processes' );

// Enqueue CSS & JS files
add_action( 'admin_enqueue_scripts', 'enqueue' );
add_action( 'wp_enqueue_scripts', function () { wp_enqueue_media ();}  );

// Custom Meta Boxes
add_action( 'show_user_profile', 'customer_service_client_permissions' );
add_action( 'edit_user_profile', 'customer_service_client_permissions' );
add_action( 'personal_options_update', 'update_permissions' );
add_action( 'edit_user_profile_update', 'update_permissions' );

// Admin Pages
add_action( 'admin_init', 'roboChat_settings_init' );