<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.10/css/all.css"
	integrity="sha384-+d0P83n9kaQMCwj8F4RJB66tzIwOKmrdb46+porD/OvrJ+37WqIM7UoBtwHO6Nlg" crossorigin="anonymous">

<div class="container-fluid" id="main-container">
	<div class="row h-100">
		<div class="col-12 col-sm-5 col-md-3 d-flex flex-column" id="chat-list-area" style="position:relative;">
			<!-- Navbar -->
			<div class="row d-flex flex-row align-items-center p-2" id="navbar">
				<div class="text-white font-weight-bold" id="username"><?php echo get_bloginfo('name');?></div>
				<div class="nav-item dropdown ml-auto text-white font-weight-bold">
					<p>
						عدد الرسائل المتبقيه :
						<span id="msgs_counter">
							<?php echo $msgs_counter; ?>
							<span>
					</p>
				</div>
			</div>

			<!-- Chat List -->
			<div class="row" id="chat-list" style="overflow:auto;">
				<?php 
		
			foreach ($main_msgs_array as $key => $contact) { 
				$arrow_class =  $contact->last_msg_direction == 1 ? "fas fa-arrow-down income-msg" :  "fas fa-arrow-up outcome-msg" ;
			?>
				<div class="chat-list-item d-flex flex-row w-100 p-2 border-bottom active" onclick="show_msgs('<?php echo message_reciever_number($key);?>')">
					<img src="<?php echo $contact['img']; ?>" alt="Profile Photo" class="img-fluid rounded-circle mr-2" style="height:50px;">
					<div class="w-50">
						<div class="name"><?php echo $contact['name']; ?></div>
						<div class="small last-message"><i class="<?php echo $arrow_class; ?>"></i>
							<?php echo $contact['last_msg']; ?></div>
					</div>
					<div class="flex-grow-1 text-right">
						<div class="small time"><?php echo $contact['last_msg_time']; ?></div>
					</div>
					<Input type ="hidden" value="<?php echo json_encode($contact['msgs']); ?>" >
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
				<a href="#"><img src="images/asd1232ASdas123a.png" alt="Profile Photo"
						class="img-fluid rounded-circle mr-2" style="height:50px;" id="pic"></a>
				<div class="d-flex flex-column">
					<div class="text-white font-weight-bold" id="name">Sanjay</div>
				</div>
			</div>

			<!-- Messages -->
			<?php 
				$n =  5 ;
				foreach ($main_msgs_array as $contact_key => $contact) {
					$n ++ ;
					$style  = $n   == 1 ? '' : 'display : none' ;
					?>

					<div class="d-flex flex-column" id="<?php echo message_reciever_number($contact_key);?>_messages" style="<?php echo $style  ; ?>" >
				<div class="mx-auto my-2 bg-primary text-white small py-1 px-2 rounded">
					25/03/2018
				</div>

				<div class="align-self-start p-1 my-1 mx-3 rounded bg-white shadow-sm message-item">
					<div class="options">
						<a href="#"><i class="fas fa-angle-down text-muted px-2"></i></a>
					</div>

					<div class="d-flex flex-row">
						<div class="body m-1 mr-2">where are you, buddy?</div>
						<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted"
							style="width:75px;">
							13:21

						</div>
					</div>
				</div>

				<div class="align-self-end self p-1 my-1 mx-3 rounded bg-white shadow-sm message-item">
					<div class="options">
						<a href="#"><i class="fas fa-angle-down text-muted px-2"></i></a>
					</div>

					<div class="d-flex flex-row">
						<div class="body m-1 mr-2">at home</div>
						<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted"
							style="width:75px;">
							13:22
							<i class="fas fa-check-circle"></i>
						</div>
					</div>
				</div>

				<div class="mx-auto my-2 bg-primary text-white small py-1 px-2 rounded">
					27/03/2018
				</div>

				<div class="align-self-end self p-1 my-1 mx-3 rounded bg-white shadow-sm message-item">
					<div class="options">
						<a href="#"><i class="fas fa-angle-down text-muted px-2"></i></a>
					</div>

					<div class="d-flex flex-row">
						<div class="body m-1 mr-2">are you going to the party tonight?</div>
						<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted"
							style="width:75px;">
							08:11
							<i class="fas fa-check-circle"></i>
						</div>
					</div>
				</div>

				<div class="align-self-start p-1 my-1 mx-3 rounded bg-white shadow-sm message-item">
					<div class="options">
						<a href="#"><i class="fas fa-angle-down text-muted px-2"></i></a>
					</div>

					<div class="d-flex flex-row">
						<div class="body m-1 mr-2">no, i've some work to do..are you?</div>
						<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted"
							style="width:75px;">
							08:22

						</div>
					</div>
				</div>
				<div class="align-self-end self p-1 my-1 mx-3 rounded bg-white shadow-sm message-item">
					<div class="options">
						<a href="#"><i class="fas fa-angle-down text-muted px-2"></i></a>
					</div>

					<div class="d-flex flex-row">
						<div class="body m-1 mr-2">yup</div>
						<div class="time ml-auto small text-right flex-shrink-0 align-self-end text-muted"
							style="width:75px;">
							08:31
							<i class="far fa-check-circle"></i>
						</div>
					</div>
				</div>
			</div>



				<?php } ?>
			<!-- Input -->
			<div class="justify-self-end align-items-center flex-row d-flex" id="input-area">
				<a href="#"><i class="far fa-smile text-muted px-3" style="font-size:1.5rem;"></i></a>
				<input type="text" name="message" id="input" placeholder="Type a message"
					class="flex-grow-1 border-0 px-3 py-2 my-3 rounded shadow-sm">
				<i class="fas fa-paper-plane text-muted px-3" style="cursor:pointer;" onclick="sendMessage()"></i>
			</div>
		</div>
	</div>
</div>