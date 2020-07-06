<?php
  // RoboChat ChatBot
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/talk_to_bot', array(
      'methods' => 'POST',
      'callback' => 'talk_to_bot',
    ) );
  } );
  
  // Api for cronjob
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/frequent_messaging', array(
      'methods' => 'POST',
      'callback' => 'frequent_messaging',
    ) );
  } );

  function frequent_messaging(){
    $subs = get_posts( 
      array(
          'numberposts' => -1,
          'post_type'   => 'shop_subscription',
          'post_status' => 'wc-active'
      )
    );
    foreach ($subs as $key => $sub_data) {
        $sub =  $sub_data->ID;
        send_unsent_queried_msgs($sub) ;
    }
  }

  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/reset_daily_msgs_counter', array(
      'methods' => 'POST',
      'callback' => 'frequent_messaging',
    ) );
  } );
  
  function reset_daily_msgs_counter(){
    $subs = get_posts( 
      array(
          'numberposts' => -1,
          'post_type'   => 'shop_subscription',
          'post_status' => 'all'
      )
    );
    foreach ($subs as $key => $sub_data) {
        $sub =  $sub_data->ID;
        update_post_meta( $sub ,'available_daily_msgs', daily_msgs );
    }
  }

  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/send_message', array(
      'methods' => 'POST',
      'callback' => 'send_message',
    ) );
  } );

  function send_message($data){

    if(token($data['sub']) != $data['token'] ){
      return array('errors' => "Not Matching Sub & token");
    }

    $required_data =  array(
      'sub','token','phone','body'
    );

    foreach ($required_data as $info) {
      if(!isset($data[$info]) ||  $data[$info] == '' ){
        return array('errors' => "Missing date: '$info' is required ");
      }
    }
    $sub    = $data['sub'];
    $token  = $data['token'];
    $type   = $data['type'];

    $parametars['phone']    = $data['phone'] ;
    $parametars['body']      = $data['body'] ;
    $parametars['caption']  = $data['caption'] ;
    $parametars['filename'] = $data['filename'] ;

    $response =  whatsapp_send_messege($sub,$parametars,$type);  

    return $response ; 
  }