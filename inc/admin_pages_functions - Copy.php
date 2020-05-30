<?php 
function render_table($headers = array() ,$body,$footers  =  false){
    echo '<table class="wp-list-table widefat fixed striped posts">';
    if(is_array($headers)){ ?>
        <thead>
            <tr>
                <?php 
                    foreach ($headers as $head) { 
                        echo "<th scope='col' id='$head' class='manage-column column-$head'>$head</th>";
                    }
                ?>
            </tr>
        </thead>
    <?php };
    if(is_array($body)){ ?>
        <thead>
                <?php 
                    foreach ($body as $row) { 
                        echo  "<tr>";
                        foreach ($row as $row_item_key => $row_item) {
                            echo "<td scope='col' id='$row_item_key' class='manage-column column-$row_item_key'>$row_item</td>";
                        }
                        echo  "</tr>";

                    }
                ?>
        </thead>
    <?php };
        if($footers ==  true ){ ?>
        	<thead>
                <tr>
                    <?php 
                        foreach ($headers as $header) { 
                            echo "<td scope='col' id='$header' class='manage-column column-$header'>$header</td>";
                        }
                    ?>
                </tr>
            </thead>
    <?php }
    echo "</table>";
}

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

function whatsapp_messeges_table($api,$token)
{
    $messeges =  whatsapp_messeges($api,$token) ;
    echo '<br><h2> عرض الرسائل  </h2><table class="wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <th scope="col" id="author" class="manage-column column-author">نص الرساله</th>
                    <th scope="col" id="categories" class="manage-column column-categories">المرسل</th>
                    <th scope="col" id="tags" class="manage-column column-tags">اسم المحادثه</th>
                </tr>
            </thead>
            <tbody id="the-list">';
            foreach($messeges as $key =>$messege){
                $time =  $messege->time ; 
                echo "
                    <tr>
                        <td>$messege->body</td>
                        <td>$messege->senderName</td>
                        <td>$messege->chatName</td>
                    </tr>
                    ";
                }
            echo "</tbody>
        </table>";
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
function whatsappapi_form (){ ?>
    <form action='options.php' method='post'>
    <?php
    settings_fields( 'pluginPage' );
    do_settings_sections( 'pluginPage' );
    submit_button('احفظ');
    ?>

</form>
<?php } ; 

function whatsappapi_processes(){ ?>

    <h2>عمليات</h2>

    <a href="<?php echo current_sub_page_url('&process=show_messeges'); ?>" class="button button-primary" > مشاهده الرسائل  </a>
    <a href="<?php echo current_sub_page_url('&process=send_messege'); ?>" class="button button-primary" > ارسال رساله نصيه </a>
    <a href="<?php echo current_sub_page_url('&process=log_out'); ?>" class="button button-primary" > انهاء الجلسه </a>

<?php };
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
function whatsapp_compose_messege($api,$token,$sub){
    ?>
    <h2>انشاء رساله جديدة</h2>
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
    <textarea  name="messege" placeholder ="نص الرساله" style="width :  90%"></textarea>
    <div class ="clients">
        <?php 
        // prepare data
        $clients =get_clients_list();

        // pre($customer_subscriptions);
        foreach ($clients as $key => $client) {
            $table_body[$key]['ID'] 			=  '<input name="phones[]" value="'.$client->ID.'" type="checkbox" id="check_'.$client->ID.'" > '; 
            $table_body[$key]['post_title'] 	=  $client->post_title;
            $table_body[$key]['phone'] 	=  $client->phone;
        }
        $header = array(
            '#'			    => ' ',
            'post_title' 	=> 'اسم العميل', 
            'phone'			=> 'رقم الهاتف',
        );
        echo "<h2>اختر عملاء الرسالة</h2>";
        render_table($header,$table_body);



$lists = get_terms([
    'taxonomy' => 'list',
    'hide_empty' => false,
]);


$table_body = array();
// pre($customer_subscriptions);
foreach ($lists as $key => $list) {
    $table_body[$key]['term_id'] 			=  '<input name="phones[]" value="'.$list->term_id.'" type="checkbox" id="check_'.$list->term_id.'" > '; 
    $table_body[$key]['name'] 	=  $list->name;
    $table_body[$key]['count'] 	=  $list->count;
}
$header = array(
    'term_id'   => ' ',
    'name'      => 'قائمه العملاء', 
    'count'     => 'عدد العملاء بداخلها',
);
echo "<h2>اختر من قوائم العملاء</h2>";

render_table($header,$table_body);



?>
    <input type="hidden" name="action" value="compose_messages">
    <input type="hidden" name="redirect" value="<?php echo current_sub_page_url('&process=send_messege'); ?>">
    <input type="hidden" name="table" value="<?php echo related_msgs_table(); ?>">

    
    <center><input style ="margin-top :  30px" class="button button-primary"  value="اضف في طابور الرسائل" type="submit" ></center>
    </form>
    </div>
<?php } ;
function message_time($time){
    return  $time ;
}
function message_reciever_number($number){
    $number  =  explode ('@' , $number);
    return $number[0] ;
}


function prepare_data($api,$token){
    $whatsapp_messeges =  whatsapp_messeges($api,$token);

    $contact_list_array[0] = array('id' => 0 , 'name' =>  'Anish' , 'number' => '91 91231 40293' , 'pic'  =>  'images/asdsd12f34ASd231.png' );
    foreach ($whatsapp_messeges as $key => $messege) {
        if($messege->fromMe == 1 ){         // CONTACT LIST 
            $me_name = $messege->senderName;
            $me_num  = $messege->author ;
            if(!$contact_list_array[0]){
                // $contact_list_array[0] = array('id' => 0, 'name' => $me_name , 'number' => $me_num ,'pic' => 'images/asdsd12f34ASd231.png' );
            }
            $reciever =   $messege->chatId ;      
        }else {
            $message_reciever_number =  message_reciever_number($messege->chatId);
            if(!$contact_list_array[$messege->id]){
                $contact_list_array[$messege->id] = array('id' => $messege->id , 'name' => $messege->chatName , 'number' => $messege->author ,'pic' => 'images/asdsd12f34ASd231.png' );
            }
            $reciever =   0 ;      
        };
        $messages_array[] = array(
            'id' => $messege->id ,
            'body' => $messege->body ,
            'time' => message_time($messege->time) ,
            'status' => 1 ,
            'recvId' => $reciever , 'recvIsGroup'=> false);
    };
    $groupList     .= '{ id: 1, name: "Programmers", members: [0, 1, 3], pic: "images/0923102932_aPRkoW.jpg" },';
        
    $messeges_list = json_encode($messages_array) ; 
    $contact_list  = json_encode($contact_list_array) ;

    echo '
        <script> 

        
let user = {
	id: 0,
	name: "'.$me_name.'",
	number: "'.$me_num.'",
	pic: "images/asdsd12f34ASd231.png"
};

let contactList = [

	'.$contact_list.'
];

let groupList = [
	{
		id: 1,
		name: "Programmers",
		members: [0, 1, 3],
		pic: "images/0923102932_aPRkoW.jpg"
	},
	{
		id: 2,
		name: "Web Developers",
		members: [0, 2],
		pic: "images/1921231232_Ag1asE.png"
	},
	{
		id: 3,
		name: "notes",
		members: [0],
		pic: "images/8230192232_asdEWq2.png"
	}
];

// message status - 0:sent, 1:delivered, 2:read

let messages = [
	'.$messeges_list.'
];

let MessageUtils = {
	getByGroupId: (groupId) => {
		return messages.filter(msg => msg.recvIsGroup && msg.recvId === groupId);
	},
	getByContactId: (contactId) => {
		return messages.filter(msg => {
			return !msg.recvIsGroup && ((msg.sender === user.id && msg.recvId === contactId) || (msg.sender === contactId && msg.recvId === user.id));
		});
	},
	getMessages: () => {
		return messages;
	},
	changeStatusById: (options) => {
		messages = messages.map((msg) => {
			if (options.isGroup) {
				if (msg.recvIsGroup && msg.recvId === options.id) msg.status = 2;
			} else {
				if (!msg.recvIsGroup && msg.sender === options.id && msg.recvId === user.id) msg.status = 2;
			}
			return msg;
		});
	},
	addMessage: (msg) => {
		msg.id = messages.length + 1;
		messages.push(msg);
	}
};
        </script>
    ';
    pre($whatsapp_messeges);
}