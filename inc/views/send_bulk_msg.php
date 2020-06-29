<?php 
    wp_enqueue_media();
    ?>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
	integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">


    <h5>انشاء رساله جديدة</h5>
	<?php 
	if(isset($_SESSION['bulk_msg_error'])){ ?>
			<div class="notice is-dismissible">
				<center><h5><?=  $_SESSION['bulk_msg_error'] ?></h5></center>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">
						Dismiss this notice.
					</span>
				</button>
			</div>

	<?php 
			unset($_SESSION['bulk_msg_error']);
} ?>
    <form action="<?= esc_url( admin_url('admin-post.php') ); ?>" method="post">
    
    <input type="hidden" name="action" value="compose_messages">
    <input type="hidden" name="redirect" value="<?= current_sub_page_url('&process=send_msg'); ?>">
    <input type="hidden" name="table" value="<?= related_msgs_table(); ?>">

    <div class="justify-self-end align-items-center flex-row d-flex" id="input-area">
				<input type='hidden' name='files' id='files' value=''>
				<input id="current_wpnonce"  name= "current_wpnonce" type ="hidden" value="<?= robo_nonce() ?>">
				<input id="sub" name="sub" type="hidden" value="<?=  get_page_sub_id() ?>" >
				<textarea name="msg" id="input" placeholder="اكتب رسالة" class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm send-bulk-msg-area auto-aligning"></textarea>
				<div class="nav-item dropdown ml-auto">
					<div class="">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
							<i id="msg_sending_btn" class="fas fa-ellipsis-v text-muted px-3" style="cursor:pointer;"> قوالب جاهزه </i>
						</a>
						<a class="nav-link">
							<i id="upload_media_button" class=" fas fa-file-image text-muted px-3" style="cursor:pointer;"> ارسال ميديا </i>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<?php
								foreach ($temps as $key => $temp) {
									echo '<a class="dropdown-item template" data-content="'.$temp->post_content.'" >'.$temp->post_title.'</a>';
								}
							?>
						</div>
					</div>
					<div class="">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
							aria-haspopup="true" aria-expanded="false">
							<i id="msg_sending_btn" class="fas fa-ellipsis-v text-muted px-3" style="cursor:pointer;">
								وجود تعبيريه
							</i>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<?php
								foreach ($emojis as $group_name => $group) {
									$group =  explode(' ' , $group);
									foreach ($group as $emoji) {
										echo '<span class="template" data-content="'.$emoji.'" >'.$emoji.'</span>';
									}
								}
							?>
						</div>
					</div>
				</div>
			</div>

			<center>
				<input style ="margin : 30px" class="button button-primary"  value="اضف رساله جديده" type="submit" >
			</center>



    <div class ="clients">
        <?php 
        // prepare data
        $clients =get_clients();
        // pre($customer_subscriptions);
        foreach ($clients as $key => $client) {
            $table_body[$key]['ID'] 			=  '<input name="clients[]" value="'.$client->ID.'" type="checkbox" id="check_'.$client->ID.'" > '; 
            $table_body[$key]['post_title'] 	=  $client->post_title;
            $table_body[$key]['phone'] 	=  $client->phone;
        }
        $header = array(
            '#'			    => ' ',
            'post_title' 	=> 'اسم العميل', 
            'phone'			=> 'رقم الهاتف',
        );
        echo "<h5  style='margon-top :  100px'>اختر عملاء الرسالة</h5>";
        render_dynamic_table($header,$table_body);



$lists = get_terms([
    'taxonomy' => 'list',
    'hide_empty' => false,
]);


$table_body = array();
foreach ($lists as $key => $list) {
    $table_body[$key]['term_id']=  '<input name="lists[]" value="'.$list->slug.'" type="checkbox" id="check_'.$list->slug.'" > '; 
    $table_body[$key]['name'] 	=  $list->name;
    $table_body[$key]['count'] 	=  $list->count;
}
$header = array(
    'term_id'   => ' ',
    'name'      => 'قائمه العملاء', 
    'count'     => 'عدد العملاء بداخلها',
);
echo "<h5  style='margon-top :  100px'>اختر قوائم العملاء</h5>";

render_table($header,$table_body);

?>    
    <center>
		<input style ="margin : 30px" class="button button-primary"  value="اضف رساله جديده" type="submit" >
	</center>
    </form>
    </div>
