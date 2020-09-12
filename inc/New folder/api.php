<?php 

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