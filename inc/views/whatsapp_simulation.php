<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css">
<div class="container-fluid" id="main-container">

	<div class="row h-100">
		<div class="col-12 col-sm-5 col-md-3 d-flex flex-column h-75" id="chat-list-area" style="position:relative;">
			<!-- Navbar -->
			<div class="row d-flex flex-row align-items-center p-2" id="navbar">
				<div class="text-white font-weight-bold" id="username"><?= get_bloginfo('name');?></div>
				<div class="nav-item dropdown ml-auto text-white font-weight-bold">
					<p>
						<?= __('عدد الرسائل المتبقيه') ?> :
						<span id="msgs_counter">
							<?= $msgs_counter; ?>
							<span>
					</p>
				</div>
			</div>

			<!-- Chat List -->
			<div class="row" id="chat-list" style="overflow:auto;">
				<?php 
		
			foreach ($main_msgs_array as $key => $contact) { 
				
				$main_id  	 =  message_reciever_number($key) ;
				$arrow_class =  $contact['last_msg_direction'] == 1 ? "fas fa-arrow-up outcome-msg" :  "fas fa-arrow-down income-msg" ;
			?>
				<div class="chat-list-item d-flex flex-row w-100 p-2 border-bottom  main-contact" id="<?= $main_id ;?>" onclick="show_msgs('<?= $main_id ;?>')" >
					<img id="<?= $main_id?>_pic" src="<?= $contact['img']; ?>" alt="Profile Photo" class="img-fluid rounded-circle mr-2" style="height:50px;">
					<div class="w-50">
						<div id="<?= $main_id?>_name" class="name">
							<?= $contact['name']; ?> 
							<i id="<?= $main_id ?>_available_icon"  class="fas <?= $contact['available_icon']; ?>"></i>	
						</div>
						<div class="small last-message"><i id="<?= $main_id?>_arrow_class" class="<?= $arrow_class; ?>"></i>
							<span id='<?= $main_id ?>_last_msg' ><?= $contact['last_msg']; ?></span>
						</div>
					</div>
					<div class="flex-grow-1 text-right">
						<div class="small time"><?= $contact['last_msg_time']; ?></div>
						<p  class="msg-counter" id="<?= $main_id ?>_msg_counter"></i>0</p>			

					</div>
					<input id="<?= $main_id ?>_hdn_inpt" type ="hidden" value='<?= json_encode($contact['msgs'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT);     ; ?>' >
					<input id="<?= $main_id ?>_available" type ="hidden" value='<?= $contact['available']; ?>' >
					<input id="<?= $main_id ?>_signature" type ="hidden" value='0' >
					<input id="<?= $main_id ?>_new_msgs_counter" type ="hidden" value='0' >
				</div>
				<?php }		
		?>
			</div>
		</div>

		<!-- Message Area -->
		<div class="d-none d-sm-flex flex-column col-12 col-sm-7 col-md-7 p-0 h-75" id="message-area">
			<div class="w-100 h-100 overlay d-none"></div>

			<!-- Navbar -->
			<div class="row d-flex flex-row align-items-center p-2 m-0 w-100" id="navbar">
				<div class="d-block d-sm-none">
					<i class="fas fa-arrow-left p-2 mr-2 text-white" style="font-size: 1.5rem; cursor: pointer;"
						onclick="showChatList()"></i>
				</div>
				<a href="#"><img src=""
						class="img-fluid rounded-circle mr-2" style="height:50px;" id="pic"></a>
				<div class="d-flex flex-column">
					<div class="text-white font-weight-bold" id="name"></div>
				</div>
			</div>

			<!-- Messages -->
			<div class="d-flex flex-column" id="messages">
				<div id="current_messages" class="d-flex flex-column">
					<div id="" class="mx-auto my-2 bg-primary text-white small py-1 px-2 rounded">
						<H2>
							<?= __('من فضلك ابقي علي هاتفك متصل بالانترنت') ?>
						</H2>
					</div>
				</div>
				<span  class="float" id="float"><i id="my-float" class="fas fa-arrow-down  my-float"></i></span>			
			</div>

			<!-- Input -->
			<div class="justify-self-end align-items-center flex-row d-flex" id="input-area">
				<span  onclick="update_session('<?= get_current_user_id();?>','<?= $_GET['page'] ?>')">
					<i id="lock" class="fas fa-lock text-muted px-3" style="font-size:1.5rem;"></i>
				</span>
				<input type='hidden' name='files' id='files' value=''>
				<input id="current_open_contacts" type ="hidden" value="">
				<input id="handle_send_msg_ability" type ="hidden" value="0">
				<input id="current_contact"  name= "current_contact" type ="hidden" value="0">
				<input id="current_wpnonce"  name= "current_wpnonce" type ="hidden" value="<?= robo_nonce() ?>">
				<input id="sub" name="sub" type="hidden" value="<?=  get_page_sub_id() ?>" >
				<input id="last_message_number" name="last_message_number" type="hidden" value="<?=  $last_message_number ?>" >
				<textarea name="input" id="input" placeholder="<?= __('اكتب رسالة') ?>" disabled="disabled" class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm"></textarea>
				
				<i id="msg_sending_btn" class="button fas fa-paper-plane text-muted px-3 send-btn" style="cursor:pointer;" onclick="sendMessage()"><?= __('ارسال'); ?></i>
 
 				<div class="nav-item dropdown ml-auto">
					<div class="">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-ellipsis-v text-muted px-3" style="cursor:pointer;"><?= __('قوالب جاهزه'); ?></i>
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
							<i class="fas fa-ellipsis-v text-muted px-3" style="cursor:pointer;">
								<?= __('وجود تعبيريه'); ?>
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
					<div class="">
						<a class="nav-link">
							<i id="upload_media_button" class=" fas fa-file-image text-muted px-3" style="cursor:pointer;"><?= __('ارسال ميديا'); ?></i>
						</a>
						<div id="image_preview_class"  >
							<center>
								<img id="image_preview" style="max-width: 75px;margin-top: 1px;" src="">
							</center>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	// let the wp media uploader include all the required files
	wp_enqueue_media();
?>
</div>