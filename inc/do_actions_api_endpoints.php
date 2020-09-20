<?php
//////////////////////////////// Internal APi ////////////////////////////////
//---------------->  ChatBot 
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/talk_to_bot', array(
      'methods' => 'POST',
      'callback' => 'internal_api_talk_to_bot',
    ) );
  } );
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/send_message', array(
      'methods' => 'POST',
      'callback' => 'internal_api_send_message',
    ) );
  } );
//---------------->  Cronjob
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/frequent_messaging', array(
      'methods' => 'GET',
      'callback' => 'internal_api_frequent_messaging',
    ) );
  } );
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/reset_daily_msgs_counter', array(
      'methods' => 'POST',
      'callback' => 'internal_api_reset_daily_msgs_counter',
    ) );
  } );
  //---------------->  Testing
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/compose_messages_handler', array(
      'methods' => 'POST',
      'callback' => 'internal_api_compose_messages_handler',
    ) );
  } );
//////////////////////////////// External APi ////////////////////////////////
//---------------->  Testing
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/subscription_status', array(
      'methods' => 'GET',
      'callback' => 'external_api_subscription_status',
    ) );
  } );

  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/instanceStatuses', array(
      'methods' => 'GET',
      'callback' => 'external_api_instanceStatuses',
    ) );
  } );
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/webhookStatus', array(
      'methods' => 'GET',
      'callback' => 'external_api_webhookStatus',
    ) );
  } );
//---------------->  instance
add_action( 'rest_api_init', function () {
  register_rest_route( slug.'/v1', '/status', array(
    'methods' => 'GET',
    'callback' => 'external_api_status',
  ) );
} );
add_action( 'rest_api_init', function () {
  register_rest_route( slug.'/v1', '/logout', array(
    'methods' => 'POST',
    'callback' => 'external_api_logout',
  ) );
} );

add_action( 'rest_api_init', function () {
  register_rest_route( slug.'/v1', '/reboot', array(
    'methods' => 'POST',
    'callback' => 'external_api_reboot',
  ) );
} );
add_action( 'rest_api_init', function () {
  register_rest_route( slug.'/v1', '/settings', array(
    'methods' => 'GET',
    'callback' => 'external_api_settings_get',
  ) );
} );
add_action( 'rest_api_init', function () {
  register_rest_route( slug.'/v1', '/settings', array(
    'methods' => 'POST',
    'callback' => 'external_api_settings_post',
  ) );
} );
/////////////// Messsages/
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/sendMessage', array(
      'methods' => 'POST',
      'callback' => 'external_api_sendMessage',
    ) );
} );
  
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/sendPTT', array(
      'methods' => 'POST',
      'callback' => 'external_api_sendPTT',
    ) );
} );
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/sendFile', array(
      'methods' => 'POST',
      'callback' => 'external_api_sendFile',
    ) );
} ); 
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/sendLink', array(
      'methods' => 'POST',
      'callback' => 'external_api_sendLink',
    ) );
} );
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/sendContact', array(
      'methods' => 'POST',
      'callback' => 'external_api_sendContact',
    ) );
} );
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/sendLocation', array(
      'methods' => 'POST',
      'callback' => 'external_api_sendLocation',
    ) );
} );
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/sendVCard', array(
      'methods' => 'POST',
      'callback' => 'external_api_sendVCard',
    ) );
} );
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/forwardMessage', array(
      'methods' => 'POST',
      'callback' => 'external_api_forwardMessage',
    ) );
} );
add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/messages', array(
      'methods' => 'GET',
      'callback' => 'external_api_messages',
    ) );
} );
//////////////---------------->  Webhook ////////////////////
add_action( 'rest_api_init', function () {
  register_rest_route( slug.'/v1', '/webhookUrl', array(
    'methods' => 'POST',
    'callback' => 'external_api_webhookUrl',
  ) );
} );