<?php
//pre($temps);

?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js"></script>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
	integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">
	
<div class="container-fluid" id="main-container">
	<div class="row h-100">
		<div class="col-12 col-sm-5 col-md-3 d-flex flex-column" id="chat-list-area" style="position:relative;">
			<!-- Navbar -->
			<div class="row d-flex flex-row align-items-center p-2" id="navbar">
				<div class="text-white font-weight-bold" id="username"><?= get_bloginfo('name');?></div>
				<div class="nav-item dropdown ml-auto text-white font-weight-bold">
					<p>
						عدد الرسائل المتبقيه :
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
				$arrow_class =  $contact->last_msg_direction == 1 ? "fas fa-arrow-down income-msg" :  "fas fa-arrow-up outcome-msg" ;
			?>
				<div class="chat-list-item d-flex flex-row w-100 p-2 border-bottom active" onclick="show_msgs('<?= $main_id ;?>')">
					<img id="<?= $main_id?>_pic" src="<?= $contact['img']; ?>" alt="Profile Photo" class="img-fluid rounded-circle mr-2" style="height:50px;">
					<div class="w-50">
						<div id="<?= $main_id?>_name" class="name">
							<?= $contact['name']; ?>
							<i id="<?= $main_id ?>_available_icon"  class="fas <?= $contact['available_icon']; ?>"></i>	
						</div>
						<div class="small last-message"><i class="<?= $arrow_class; ?>"></i>
							<?= $contact['last_msg']; ?></div>
					</div>
					<div class="flex-grow-1 text-right">
						<div class="small time"><?= $contact['last_msg_time']; ?></div>
					</div>
					<input id="<?= $main_id ?>_hdn_inpt" type ="hidden" value='<?= json_encode($contact['msgs']); ?>' >
					<input id="<?= $main_id ?>_available" type ="hidden" value='<?= $contact['available']; ?>' >
					<input id="<?= $main_id ?>_signature" type ="hidden" value='0' >
				</div>
				<?php }		
		?>
			</div>
			<!-- Profile Settings -->
			<div class="d-flex flex-column w-100 h-100" id="profile-settings">
				<div class="row d-flex flex-row align-items-center p-2 m-0"
					style="background:#009688; min-height:65px;">
					<i class="fas fa-arrow-left p-2 mx-3 my-1 text-white" style="font-size: 1.5rem; cursor: pointer;"
						onclick="hideProfileSettings()"></i>
					<div class="text-white font-weight-bold">Profile</div>
				</div>
				<div class="d-flex flex-column" style="overflow:auto;">
					<img alt="Profile Photo" class="img-fluid rounded-circle my-5 justify-self-center mx-auto"
						id="profile-pic">
					<input type="file" id="profile-pic-input" class="d-none">
					<div class="bg-white px-3 py-2">
						<div class="text-muted mb-2"><label for="input-name">Your Name</label></div>
						<input type="text" name="name" id="input-name" class="w-100 border-0 py-2 profile-input">
					</div>
					<div class="text-muted p-3 small">
						This is not your username or pin. This name will be visible to your WhatsApp contacts.
					</div>
					<div class="bg-white px-3 py-2">
						<div class="text-muted mb-2"><label for="input-about">About</label></div>
						<input type="text" name="name" id="input-about" value=""
							class="w-100 border-0 py-2 profile-input">
					</div>
				</div>

			</div>
		</div>

		<!-- Message Area -->
		<div class="d-none d-sm-flex flex-column col-12 col-sm-7 col-md-7 p-0 h-100" id="message-area">
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
				<div class="mx-auto my-2 bg-primary text-white small py-1 px-2 rounded">
					<H2>
						من فضلك ابقي علي هاتفك متصل بالانترنت
					</H2>
				</div>				
			</div>

			<!-- Input -->
			<div class="justify-self-end align-items-center flex-row d-flex" id="input-area">
				<span  onclick="update_session('<?= get_current_user_id();?>','<?= $_GET['page'] ?>')">
					<i id="lock" class="fas fa-lock text-muted px-3" style="font-size:1.5rem;"></i>
				</span>
				<input id="current_open_contacts" type ="hidden" value="">
				<input id="handle_send_msg_ability" type ="hidden" value="0">
				<input id="current_contact"  name= "current_contact" type ="hidden" value="0">
				<input id="current_sub" name="current_sub" type="hidden" value="<?=  get_page_sub_id() ?>" >
				<textarea name="input" id="input" placeholder="اكتب رسالة" disabled="disabled" class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm"></textarea>
				<i id="msg_sending_btn" class="fas fa-paper-plane text-muted px-3" style="cursor:pointer;" onclick="sendMessage()"></i>
				<div class="nav-item dropdown ml-auto">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
						<i id="msg_sending_btn" class="fas fa-ellipsis-v text-muted px-3" style="cursor:pointer;"> قوالب جاهزه </i>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<?php
								foreach ($temps as $key => $temp) {
									echo '<a class="dropdown-item template" data-content="'.$temp->post_content.'" >'.$temp->post_title.'</a>';
								}
							?>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>




<script>
	function societ_listen(link , action){
		var socket =  io.connect(link);
		socket.on(action , function (data){
			if(data.action ==  'update_session'){
				console.log(data);
				update_session_graphical(data.contact,data.user , '<?= get_current_user_id();?>');
			}
			// if new messege comming 
			// if onother one make a massege the counter must be descrease 
		})
	}
	societ_listen('<?= societ_link; ?>' , '<?= societ_action; ?>');
</script>