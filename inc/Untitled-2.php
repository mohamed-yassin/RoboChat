//////////////////////////////// Internal APi ////////////////////////////////
//---------------->  ChatBot 
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/talk_to_bot', array(
      'methods' => 'POST',
      'callback' => 'internal_api_talk_to_bot',
      $data ,  false)  } );
  } );


  
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/send_message', array(
      'methods' => 'POST',
      'callback' => 'internal_api_send_message',
      $data ,  false)  } )
  } );
//---------------->  Cronjob
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/frequent_messaging', array(
      'methods' => 'POST',
      'callback' => 'internal_api_frequent_messaging',
      $data ,  false)  } )
  } );
  add_action( 'rest_api_init', function () {
    register_rest_route( slug.'/v1', '/reset_daily_msgs_counter', array(
      'methods' => 'POST',
      'callback' => 'internal_api_reset_daily_msgs_counter',
      $data ,  false)  } )
  } );