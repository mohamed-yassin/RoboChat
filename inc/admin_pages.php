<?php

function robo_add_admin_menu(  ) { 
	if(is_super_admin()){
		add_menu_page( 'RoboChat', 'RoboChat', 'manage_options', 'robochat', 'robo_orders_admin_page_render', 'dashicons-buddicons-buddypress-logo' , 2 );
	}
}

function robo_orders_admin_page_render(){

	// Page Header
	echo "<h2> Subscriptions Orders</h2>";

	// prepare data
	$customer_subscriptions = get_posts( 
		array(
			'numberposts' => -1,
			'post_type'   => 'shop_subscription',
			'post_status' => 'wc-active' // because the defult of the wp is publish so it will not get data 
		)
	);

	// pre($customer_subscriptions);
	foreach ($customer_subscriptions as $key => $sub) {
		$table_body[$sub->ID]['ID'] 			=  $sub->ID;
		$table_body[$sub->ID]['post_title'] 	=  "<a href='".get_edit_post_link($sub->ID)."'>$sub->post_title</a>";
		$table_body[$sub->ID]['post_author'] 	=  "<a href='".get_edit_user_link($sub->post_author)."'>".not_null_auther_name($sub->post_author)."</a>";
		$table_body[$sub->ID]['post_status'] 	=  $sub->post_status ==  'wc-active'  ?  "Active" :  "Not Active";
		$table_body[$sub->ID]['gate_way'] 		=  get_field('gate_way',$sub->ID) != ''  ?  'Chat API'   : "Not Defined Yet";
		$table_body[$sub->ID]['details'] 		=  "<a href='".get_edit_post_link($sub->ID)."'>details</a>";
	}
	$header = array(
		'id'			=> 'ID',
		'post_title' 	=> 'Name', 
		'post_author' 	=> 'Client',
		'config' 	    => 'Status',
		'gate_way'		=> 'Gate Way',
		'details'		=> 'Details',
	);
	render_table($header,$table_body);
}

function client_dashboard(  ) { 
	$option_name   =  subs_option_field_name();
    $user_blogs =  get_option( $option_name );
	$user_blogs = json_decode($user_blogs, true);

	if(!is_super_admin()){
		foreach ($user_blogs as $blog) {
			add_menu_page( "#$blog", "#$blog", 'manage_options', "sub_$blog", 'whatsappapi_options_page' , '' , 3);
		}
	}
}

function whatsappapi_options_page(  ) { 
	$sub 				 = get_page_sub_id();
	$sub_connection_data = sub_connection_data($sub);
	$api   				 = $sub_connection_data['api'];
	$token 				 = $sub_connection_data['token'];

	if($api == '' || $token == '' ){
		echo "<h2>" .  "من فضلك تواصل مع قسم المبيعات او الدعم الفني للتحقق من تفعيل حسابك" . "</h2>" ;
	}else {
		$sub_status =  sub_status($api,$token); // later we will add some security here to check if the user has can go or not 
		if($sub_status['accountStatus'] == 'authenticated'){
			whatsappapi_processes();
			$process = $_GET['process'] ;

			if($process != ''){
				if($process== 'send_messege'){
					//whatsapp_send_messege($api,$token);
					whatsapp_compose_messege($api,$token,$sub);
				}elseif ($process  == 'show_messages') {
					whatsapp_messeges_table($api,$token);
					//view('whatsapp_simulation2');
				}elseif ($process  == 'log_out') {
					whatsapp_log_out($api,$token,$sub);
				}
			}
		}else{
			whatsappapi_authen($sub_status['qrCode']);
		}	
	}
}