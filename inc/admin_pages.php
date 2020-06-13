<?php


function robo_add_admin_menu() { 
	if(get_current_blog_id() == 1 ){
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

	if(get_current_blog_id() != 1  &&  is_array($user_blogs)){
		foreach ($user_blogs as $blog) {
			add_menu_page( "#$blog", "#$blog", 'manage_options', "sub_$blog", 'whatsappapi_options_page' , '' , 3);
		}
	}
}

function whatsappapi_options_page() { 
	$sub 				 = get_page_sub_id();
	$sub_connection_data = sub_connection_data($sub);
	$api   				 = $sub_connection_data['api'];
	$token 				 = $sub_connection_data['token'];

	if($api == '' || $token == ''){
		echo "<h2>" .  "من فضلك تواصل مع قسم المبيعات او الدعم الفني للتحقق من تفعيل حسابك" . "</h2>" ;
	}else {
		$sub_status =  sub_status($api,$token); // later we will add some security here to check if the user has can go or not 
		if($sub_status['accountStatus'] == 'authenticated'){
			whatsappapi_processes();
			$process = $_GET['process'] ;

			if($process != ''){
				if($process== 'send_msg'){
					whatsapp_compose_messege($api,$token,$sub);
				}elseif ($process  == 'show_msgs') {
					$data['msgs_counter'] =  sub_connection_data($sub)['msgs'];
					$data['msgs_counter'] =  $data['msgs_counter'] >  0 ? $data['msgs_counter'] : 'لقد نفذ الرصيد اليومي' ;					
					$whatsapp_messeges =  whatsapp_messeges($api,$token);
					$prepare_msgs =  prepare_msgs($whatsapp_messeges);
					$data['main_msgs_array'] = $prepare_msgs ;
					$data['temps'] = get_templates();
					view('whatsapp_simulation', $data);
				}
			}
		}elseif ($sub_status['accountStatus'] == 'loading') {
			echo "هناك مشكله :: </br>
			  	1-  تاكد ان هاتفك متصل بالانترنت </br>
			   	2- وفتح برنامج الواتساب علي الموبايل </br>
			 	3 - انهاء توصيل الواتساب الخاص بك مع اي خدمات اخري  </br>
			   " ;
		}elseif (! isset($sub_status['accountStatus'])) {
			echo  "هناك مشكله في معلومات الاتصال ,  , من فضلك راسل خدمه العملاء" ;
		}else{
			whatsappapi_authen($sub_status['qrCode']);
		}	
	}
}