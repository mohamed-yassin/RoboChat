<?php 

function internal_api_frequent_messaging(){
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

function internal_api_reset_daily_msgs_counter(){
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

function internal_api_send_message($data){

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
