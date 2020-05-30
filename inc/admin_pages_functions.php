<?php 
function whatsappapi_settings_init(  ) { 

	register_setting( 'pluginPage', 'whatsappapi_settings' );

	add_settings_section(
		'whatsappapi_pluginPage_section', 
		__( 'تفاصيل الاتصالات', 'whatsappapi' ),  // Section title 
		'whatsappapi_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'whatsappapi_text_field_0', 
		__( 'رابط ال API ', 'whatsappapi' ), 
		'whatsappapi_text_field_0_render', 
		'pluginPage', 
		'whatsappapi_pluginPage_section' 
	);

	add_settings_field( 
		'whatsappapi_text_field_1', 
		__( 'ال TOKEN ', 'whatsappapi' ), 
		'whatsappapi_text_field_1_render', 
		'pluginPage', 
		'whatsappapi_pluginPage_section' 
	);

	add_settings_field( 
		'whatsappapi_text_field_2', 
		__( 'رقم هاتف مستقبل الرسائل', 'whatsappapi' ), 
		'whatsappapi_text_field_2_render', 
		'pluginPage', 
		'whatsappapi_pluginPage_section' 
    );
    add_settings_field( 
		'whatsappapi_text_field_3', 
		__( 'رقم هاتف مستقبل الرسائل', 'whatsappapi' ), 
		'whatsappapi_text_field_3_render', 
		'pluginPage', 
		'whatsappapi_pluginPage_section' 
	);

}


function whatsappapi_text_field_0_render(  ) { 

	$options = get_option( 'whatsappapi_settings' );
	?>
	<input style="width :  80%" type='text' name='whatsappapi_settings[whatsappapi_text_field_0]' value='<?php echo $options['whatsappapi_text_field_0']; ?>'>
	<?php

}


function whatsappapi_text_field_1_render(  ) { 

	$options = get_option( 'whatsappapi_settings' );
	?>
	<input style="width :  80%" type='text' name='whatsappapi_settings[whatsappapi_text_field_1]' value='<?php echo $options['whatsappapi_text_field_1']; ?>'>
	<?php

}


function whatsappapi_text_field_2_render(  ) { 

	$options = get_option( 'whatsappapi_settings' );
	?>
	<input style="width :  80%" type='text' name='whatsappapi_settings[whatsappapi_text_field_2]' value='<?php echo $options['whatsappapi_text_field_2']; ?>'>
	<?php

}
function whatsappapi_text_field_3_render(  ) { 

	$options = get_option( 'whatsappapi_settings' );
	?>
	<input style="width :  80%" type='text' name='whatsappapi_settings[whatsappapi_text_field_3]' value='<?php echo $options['whatsappapi_text_field_3']; ?>'>
	<?php

}



function whatsappapi_settings_section_callback(  ) { 

	echo __( '', 'whatsappapi' ); //  This section description

}


function whatsapp_messeges($api,$token,$filters= array())
{
    $filter_tas_string =  "";
    if(is_array($filters)){
        foreach ($filters as $key => $filter) {
            $filter_tas_string .=  '';
        }
    }
    $url = $api.'messages?token='.$token.$filter_tas_string;


    $result = file_get_contents($url);
    $result = (array)json_decode($result);
    // pre($result);
    return $result['messages'];
}
function whatsapp_send_messege($api,$token)
{

    $options        = get_option('whatsappapi_settings' );
    $phone_reciever = '00201096808707';
    $messege        = date('Y:M');

    $url            = $url = $api.'sendMessage?token='.$token;

    $data = [
        'phone' => $phone_reciever, // Receivers phone
        'body' => $messege, // Message
    ];
    $json = json_encode($data); // Encode data to JSON

    // Make a POST request
    $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $json
        ]
    ]);
    // Send a request
    $result = array();
    $result = file_get_contents($url, false, $options);

    echo "<h2>تم تسليم الرساله بنجاح في حاله قمت بارسال الرسائل بشكل متكرر سيستقبل 
    </br>الapi 
    </br>
    الرساله ولكن لن يرسلها شكا في انها ربما تكون 
    </br>ال spam 
    
    </h2>" ;
    
}
function whatsapp_log_out($api,$token,$sub)
{

    $url            = $url = $api.'logout?token='.$token;
    $data = [
    ];
    $json = json_encode($data); // Encode data to JSON

    // Make a POST request
    $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $json
        ]
    ]);
    // Send a request
    $result = array();
    $result = file_get_contents($url, false, $options);
    echo '<a href="'.admin_url('admin.php?page='.$sub.'&process=log_out').'" class="button button-primary" > انهاء الجلسه </a>';

    
}
function whatsappapi_authen ($qr){
    echo '<h2>امسح الكود باستخدام برنامج الواتساب</h2>';
    echo '<img src="'.$qr.'" alt="Base64 encoded image"/>';
?>

    
    <script type="text/javascript">
    function countdown() {
        var i = document.getElementById('counter');
        if (parseInt(i.innerHTML)<=0) {
            location.href = '<?php echo get_the_full_sub_page_url();?>';
            location.href = '#';
        }
    if (parseInt(i.innerHTML)!=0) {
        i.innerHTML = parseInt(i.innerHTML)-1;
    }
    }
    setInterval(function(){ countdown(); },1000);
    </script>
<?php };
function current_sub_page_url($extention = NULL)
{
    return admin_url('admin.php?page='.$_GET['page'].$extention);
}
function get_page_sub_id()
{
    $sub = $_GET['page'];
	$sub = explode('_', $sub);
    $sub = $sub[1];
    
    return  $sub;
}
function sub_status($api,$token){
    $login =  whatsapp_login($api,$token);
    return  $login ;
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
function whatsapp_login($api,$token)
{
    $url =   $api.'status?token='.$token;
    $result = @file_get_contents($url);
    $result = (array)json_decode($result);

    return $result;
}
function get_clients_list(){
    $args =  array(
        'post_type' =>'clients',
    );
    $clients =  (array)get_posts($args);
    foreach ($clients as $key => $client) {
        $clients[$key]->phone = get_field('phone',$client->ID);
    }
    return $clients;
}
function message_time($time){
    return  $time ;
}
function message_reciever_number($chatid){
    $number  =  explode ('@' , $chatid);
    $number  =  pure_phone ($number[0]);
    return $number ;
}

function is_group($chatId){
    $id   =  explode ('@' , $chatId);
    $type =  explode ('.' , $id[1])[0];

    return $type == 'g' ?  true :  false ;
}
function contact_info($number){
    $args = array(
        'post_type'  => 'clients',
        'meta_query' => array(
            array(
                'key'     => 'phone',
                'value'   => pure_phone($number) ,
                'compare' => '=',
            ),
        ),
    );
    $contacts =  get_posts($args);
    $return  =  is_array($contacts) && count($contacts) >  0 ?  array('name' => $contacts[0]->post_title , 'img' => get_the_post_thumbnail_url($contacts[0]->ID ) ) :  array() ;
    return $return  ;
}
function prepare_msgs($msgs){
        foreach ($msgs as $key => $msg) {
            if(!is_group($msg->chatId)){
                $msg->real_time = date("H:i",$msg->time);
                $prepared_msgs[$msg->chatId]['msgs'][msg_day($msg->time)][$msg->time] =  $msg ;
                $prepared_msgs[$msg->chatId]['last_msg'] =   isset($prepared_msgs[$msg->chatId]['last_msg']) && $prepared_msgs[$msg->chatId]['last_msg'] >   $msg->time  ? $prepared_msgs[$msg->chatId]['last_msg'] :  $msg->body   ;
                if(! isset($prepared_msgs[$msg->chatId]['last_msg']) ||  $prepared_msgs[$msg->chatId]['last_msg'] <   $msg->time  ){
                    $prepared_msgs[$msg->chatId]['last_msg']      = $msg->body;
                    $prepared_msgs[$msg->chatId]['original_last_msg_time'] = $msg->time;
                    $prepared_msgs[$msg->chatId]['last_msg_time'] = msg_time($msg->time);
                    $prepared_msgs[$msg->chatId]['last_msg_direction'] =  $msg->fromMe ;  // from me 1 else 0 or impty
                }
                $contact_info = contact_info(message_reciever_number($msg->chatId)) ;
                $prepared_msgs[$msg->chatId]['name'] =  isset($contact_info['name']) ? $contact_info['name'] :  $msg->chatName ;
                $prepared_msgs[$msg->chatId]['img']  =  $contact_info['img'] != '' ? $contact_info['img']   :  dflt_user_img ;                    
            }
        }
        array_multisort(array_column($prepared_msgs, 'original_last_msg_time'), SORT_DESC, $prepared_msgs);
        return $prepared_msgs  ;
}
function pure_phone($num){
    return  (int) preg_replace('/\D/', '', $num); 
}
function filter_phone_number( $value, $post_id, $field ) {
    pre($_REQUEST);
    return pure_phone($value);  
}
function msg_time($time){
    $time =  date("H:i d/m",$time) ;
    return  $time; 
}
function  msg_day($time){
    return date("d/m",$time) ;
}