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
			'post_status' => 'all' // because the defult of the wp is publish so it will not get data 
		)
	);
	$conditions =  array(
		'wc-active' 	=> 'Active',
		'wc-on-hold' 	=> 'On Hold',
		'wc-cancelled' 	=> 'Cancelled',
		'wc-pending' 	=> 'Pending',
	);
	foreach ($customer_subscriptions as $key => $sub) {
		$table_body[$sub->ID]['ID'] 			=  $sub->ID;
		$table_body[$sub->ID]['post_title'] 	=  "<a href='".get_edit_post_link($sub->ID)."'>$sub->post_title</a>";
		//$table_body[$sub->ID]['post_author'] 	=  "<a href='".get_edit_user_link($sub->post_author)."'>".not_null_auther_name($sub->post_author)."</a>";
		$table_body[$sub->ID]['post_status'] 	=  $conditions[$sub->post_status];
		$table_body[$sub->ID]['gate_way'] 		=  get_field('gate_way',$sub->ID) != ''  ?  'Chat API'   : "Not Defined Yet";
		$table_body[$sub->ID]['details'] 		=  "<a href='".get_edit_post_link($sub->ID)."'>details</a>";
	}
	$header = array(
		'id'			=> 'ID',
		'post_title' 	=> 'Name', 
		//'post_author' 	=> 'Client',
		'config' 	    => 'Status',
		'gate_way'		=> 'Gate Way',
		'details'		=> 'Subscription Details',
	);
	render_table($header,$table_body);
}
function client_dashboard(  ) { 
	$user_blogs 	= subs_option_field_array();
	if(get_current_blog_id() != 1  &&  is_array($user_blogs)){
		add_menu_page( "RoboChat Settings", "RoboChat Settings", 'manage_options', "robochat_settings", 'roboChat_options_page' , '' , 3);
		$options = get_option( 'roboChat_settings' );
		foreach ($user_blogs as $blog) {
			$blog_name =  isset($options['sub_'.$blog.'_name'])  &&  $options['sub_'.$blog.'_name'] != ''  ?  $options['sub_'.$blog.'_name'] :  "#$blog";
			add_menu_page( "$blog_name", "$blog_name", 'manage_options', "sub_$blog", 'whatsappapi_options_page' , '' , 3);
		}
	}
}
function robochat_settings(){ ?>
	<h2>الاعدادات العامه</h2>
	<form action='options.php' method='post'>
	<h3>اعاده تسميه الاشتراكات</h3>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>

<?php 
}
function whatsappapi_options_page() { 
	$sub 				 = get_page_sub_id();
	$woocommerce_sub	 = get_post_data_from_the_main_blog($sub);
	$woocommerce_status	 = $woocommerce_sub->post_status;
	
	if($woocommerce_status == 'wc-active'){
		$sub_connection_data = sub_connection_data($sub);
		$api   				 = $sub_connection_data['api'];
		$token 				 = $sub_connection_data['token'];

		if($api == '' || $token == ''){
			echo "<h2>" .  "من فضلك تواصل مع قسم المبيعات او الدعم الفني للتحقق من تفعيل حسابك" . "</h2>" ;
		}else {
			$chatapi_sub_status =  chatapi_sub_status($api,$token);
			if(isset($chatapi_sub_status['accountStatus']) && $chatapi_sub_status['accountStatus'] == 'authenticated'){
				whatsappapi_processes();
				$process = isset($_GET['process']) ? $_GET['process'] :  "" ;

				if($process != ''){
					if($process== 'send_msg' && has_robo_permission('send_msg')){
						$data['emojis'] = get_emojis();
						$data['temps'] = get_templates();
						$data['api'] = $api;
						$data['token'] = $token;
						$data['sub'] = $sub;
						view('send_bulk_msg', $data);
					}elseif ($process  == 'show_msgs' && has_robo_permission('show_msgs') ) {
						$data['msgs_counter'] =  sub_connection_data($sub)['msgs'];
						$data['msgs_counter'] =  $data['msgs_counter'] >  0 ? $data['msgs_counter'] : 'لقد نفذ الرصيد اليومي' ;	
						$whatsapp_messeges =  whatsapp_messeges($api,$token);
						$prepare_msgs =  $whatsapp_messeges->messages;
						$prepare_msgs =  prepare_msgs($prepare_msgs);
						$data['main_msgs_array'] = $prepare_msgs ;
						$data['last_message_number'] = $whatsapp_messeges->lastMessageNumber ;
						$data['temps'] = get_templates();
						$data['emojis'] = get_emojis();
						view('whatsapp_simulation', $data);
					}elseif ($process  == 'show_msgs') {
						
					}else {
						echo "عذرا رابط  غير صحيح او لا تملك الصلاحيات";
					}
				}
			}else {
				if(production  !=  true ){
					pre($chatapi_sub_status);
				}
				if(isset($chatapi_sub_status['accountStatus']) &&  $chatapi_sub_status['accountStatus'] == 'loading') {
					echo "هناك مشكله :: </br>
						1-  تاكد ان هاتفك متصل بالانترنت </br>
						2- وفتح برنامج الواتساب علي الموبايل </br>
						3 - انهاء توصيل الواتساب الخاص بك مع اي خدمات اخري  </br>
					" ;
				}elseif (! isset($chatapi_sub_status['accountStatus'])) {
					echo  "هناك مشكله في معلومات الاتصال ,  , من فضلك راسل خدمه العملاء" ;
				}else{
					whatsappapi_authen($chatapi_sub_status['qrCode']);
				}	
			}
		}
	}else {
		echo " لا يمكن الدخول الي الحساب من فضلك راسل الاداره لتفعيل الحساب" ;
	}
}
add_action( 'admin_init', 'roboChat_settings_init' );


function roboChat_settings_init(  ) { 

	register_setting( 'pluginPage', 'roboChat_settings' );

	add_settings_section(
		'roboChat_pluginPage_section', 
		__( '', 'robo' ), 
		'roboChat_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'roboChat_text_field_0', 
		__( 'Rename Subscriptios', 'robo' ), 
		'roboChat_text_field_0_render', 
		'pluginPage', 
		'roboChat_pluginPage_section' 
	);


}


function roboChat_text_field_0_render(  ) { 
	$user_blogs 	= subs_option_field_array();
	$options = get_option( 'roboChat_settings' );

	if(get_current_blog_id() != 1  &&  is_array($user_blogs)){
		foreach ($user_blogs as $blog) { 
			$name=  'sub_'.$blog.'_name';
			?>
			<tr>
				<th scope="row">Subscriptions #<?= $blog ?></th>
				<td>
					<input type="text" name="roboChat_settings[<?= $name ?>]" value='<?=  isset($options[$name]) ?  $options[$name] :  '' ; ?>'>
				</td>
			</tr>
		<?php }
	}

	?>	
	<?php
}


function roboChat_settings_section_callback(  ) { 

	echo __( '', 'robo' );

}


function roboChat_options_page(  ) { 
		?>
		<form action='options.php' method='post'>

			<h2>RoboChat Settings</h2>

			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>

		</form>
		<?php
}