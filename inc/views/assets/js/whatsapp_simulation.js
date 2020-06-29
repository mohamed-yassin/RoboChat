function show_msgs(id,new_msgs=0,fake=0,reset_counter=0,active_this=0) {
	if(reset_counter == 1){
		reset_msg_counters();
	}
	var msgs_box = 'current_messages';
	document.getElementById("name").innerHTML  =  document.getElementById(id+"_name").innerHTML ;
	document.getElementById("pic").src = document.getElementById(id+"_pic").src;
	document.getElementById(msgs_box).innerHTML = '';
	document.getElementById('current_contact').value =  id ;
	handle_send_msg_ability();
	
	var contact_msgs = JSON.parse(get_msgs_from_hdn_inpt(id));
	jQuery.each(contact_msgs , function( key, msg ){
		if(!(fake ==  0 &&  msg.fake == 1) ){
			//append_msg_html(msg);
		}
		append_msg_html(msg);
	});	

	//
	document.getElementById(id+"_last_msg").classList.remove('font-weight-bold');
	document.getElementById("files").value = '';

	if(new_msgs >  0){
		document.getElementById('float').style.display = 'block'; 
		document.getElementById("my-float").innerHTML = new_msgs ;	
	}else{
		scroll_to_last_msg();
	}
};
function scroll_to_last_msg(){
	var element = document.getElementById('messages');
	element.scrollTop = element.scrollHeight;	
	var current_contact =  document.getElementById('current_contact').value ; 
	reset_msg_counters ();
}
function append_msg_html(msg_data) {

	msg = msg_data.body;
	date = msg_data.real_time;
	code =  msg_data.fromMe;
	type =  msg_data.type;
	caption =  msg_data.hasOwnProperty('caption') ?  msg_data.caption :  "";

	if(code  == 0){ // recieved
		var class_name =  "align-self-start ";
		var status =  "";
	}else if(code  == 1){ // sent succesfully
		var class_name =  "align-self-end self ";
		var status =  " " + "<i class='fas fa-check  income-msg'></i>" + " ";
	}else if(code  == 2){ // msgs balance ranout
		var class_name =  "align-self-end self ";
		var status =  " " + "<i class='fas fa-battery-quarter  outcome-msg'></i>" + " ";
	}else if(code  == 3){ // chat api error
		var class_name =  "align-self-end self ";
		var status =  " " + "<i class='fas fa-times-circle  outcome-msg'></i>" + " ";
	}else if(code  == 4){ // sending now ... 
		var class_name =  "align-self-end self ";
		var status =  " " + "جاري الارسال<i class='fas fa-spinner fa-spin income-msg'></i>" + " ";		
	}
	if(type  == 'image' ){ // for media files
		msg_div = "<div class='";
		msg_div += class_name ;
		msg_div += " p-1 my-1 mx-3 rounded bg-white shadow-sm message-item'><div class='d-flex flex-row media-message'>";
		msg_div += "<img class='img-fit' src='" + msg +  "'></div>"; 
		msg_div += "<div class='d-flex flex-row'><div>"+caption+"</div>";
		msg_div += "<span class='time ml-auto small  flex-shrink-0 align-self-end text-muted time-media'>"+date+"</span></div></div>";
	}else{ //  text or type ==  1
		msg_div = "<div class='";
		msg_div += class_name ;
		msg_div += " p-1 my-1 mx-3 rounded bg-white shadow-sm message-item'><div class='d-flex flex-row'><div class='body m-1 mr-2'>";
		msg_div += status; 
		msg_div += msg; 
		msg_div += "</div><div class='time ml-auto small text-right flex-shrink-0 align-self-end text-muted'>";
		msg_div += date ;
		msg_div += "</div></div></div>";
	}
	jQuery("#current_messages").append(msg_div);
};

function get_msgs_from_hdn_inpt(id) {
	id =  id+"_hdn_inpt";
	return document.getElementById(id).value ;
};
function obj_into_array(obj){
	var array = Object.keys(obj).map(function(key) {
		return [Number(key), obj[key]];
	  });
	return array; 
};
function obj_to_array(obj){
	var array = Object.entries(obj);
	return array; 
}
function handle_send_msg_ability() {
	var current_contact =  document.getElementById('current_contact').value ; 
	if(current_contact !=  0){
		var current_contact_status = document.getElementById(current_contact +"_available").value  ;
		if(current_contact_status == 2 ){ // if it allready available for the user : close it 
			document.getElementById("input").disabled = false ;
			document.getElementById("upload_media_button").disabled = false ;
	
			document.getElementById("lock").classList.add('fa-lock-open');
			document.getElementById("lock").classList.remove('fa-lock');
		}else{ 
			document.getElementById("input").disabled = true ;
			document.getElementById("upload_media_button").disabled = true ;
	
			document.getElementById("lock").classList.add('fa-lock');
			document.getElementById("lock").classList.remove('fa-lock-open');
		}
		
	}
}
function sendMessage (){
	var msg   = document.getElementById('input').value ;
	var files = document.getElementById('files').value ; 

	document.getElementById('input').value  = '';
	if(msg.length  > 0 || files.length > 0  ){
		var num   = document.getElementById('current_contact').value ; 
		var sub   = document.getElementById('sub').value ; 
		var _wpnonce = document.getElementById('current_wpnonce').value ; 
		var sign  = document.getElementById(num+'_signature').value ; 
		var last_message_number = document.getElementById("last_message_number").value ;

		jQuery.ajax({
			type: "post",url: "admin-ajax.php",data: { action: 'send_instant_msg_action' , _wpnonce: _wpnonce , msg :  msg , num : num , sub : sub , sign :  sign , files : files , last_message_number :  last_message_number },
			success: function(rsp){ 
				document.getElementById("msgs_counter").innerHTML = rsp.balance;
				var msgs =	rsp.msgs;
				update_msgs_graphical(msgs);		
				document.getElementById(num+'_signature').value =  2 ;
			}
		});	
	}else{
		alert('رساله فارغه');
	}
}
function append_sent_msg(data){


	append_msg_html(real_msg.body, real_msg.real_time , real_msg.fromMe ,  real_msg.type ,real_msg.caption );

	var today = new Date();
	var time  = today.getHours() + ":" + today.getMinutes();
	var status = data.status;
	var num =  data.phone;
	var msg = data.body; 
	if(status == 1){ // send successfully 
		document.getElementById("msgs_counter").innerHTML = data.balance;
		document.getElementById(num + "_signature").value = 2;
	}else if(status == 2){ // if daily msg balance is ranout
		document.getElementById("msgs_counter").innerHTML = "لقد نفذ الرصيد يرجي التجديد";
	}
	append_msg_html(msg,time,status);
}
function update_session(current_user){
	
	var current_contact =  document.getElementById('current_contact').value ;
	var sub =  document.getElementById('sub').value ;
	var available =  document.getElementById(current_contact +'_available').value ;

	if(current_contact !=  0){ // if their is a selected contact 
		if(2 > 1){
			var current_contact_status = document.getElementById(current_contact +"_available").value  ;
			jQuery.ajax({
				type: "post",url: "admin-ajax.php",data: { action: 'update_session_action' , contact : current_contact , user : current_user , sub : sub  },
				success: function(sessions){ 

					jQuery.each(sessions , function( session_key, session ){
						update_session_graphical(session_key,session);
					});
					document.getElementById('current_contact').value =  current_contact ;
					handle_send_msg_ability();
				}
			});	
		}
	}
}
function pre(obj){
	let keepKeyOrder = function(obj) {
		if (typeof obj === 'object' && !Array.isArray(obj)) {
			let transformKey = (k) => [k, keepKeyOrder(obj[k])];
			return Object.keys(obj).map(transformKey);
		} else {
			return obj;
		}
	};
	console.log(keepKeyOrder(obj));
}
function update_session_graphical(current_contact,session){
	
	var status_code =  session[0];
	

	if(document.getElementById(current_contact +"_available") !== null)
	{
		document.getElementById(current_contact +"_available").value = status_code;
		if(status_code == 2 ){  // current user serving
			document.getElementById(current_contact + "_available_icon").classList.add('fa-comments');
			document.getElementById(current_contact + "_available_icon").classList.remove('outcome-msg');
			document.getElementById(current_contact + "_available_icon").classList.add('income-msg');
			
			// document.getElementById(current_contact + "_signature").value = 1; make problem : after the 
		}else if(status_code == 0){ // another user serving
			document.getElementById(current_contact + "_available_icon").classList.add('fa-comments');
			document.getElementById(current_contact + "_available_icon").classList.remove('income-msg');
			document.getElementById(current_contact + "_available_icon").classList.add('outcome-msg');
			document.getElementById(current_contact + "_signature").value = 0  ;
		}else{ // no one serving 
			document.getElementById(current_contact + "_available_icon").classList.remove('fa-comments'); 
			document.getElementById(current_contact + "_available_icon").classList.remove('income-msg');
			document.getElementById(current_contact + "_available_icon").classList.remove('outcome-msg');
			document.getElementById(current_contact + "_signature").value = 0  ;
		}
	}else{
		// crete a new div;
	}
};
function update_data(){
	var last_message_number = document.getElementById("last_message_number").value ;
	var _wpnonce 	= document.getElementById('current_wpnonce').value ; 
	var sub = document.getElementById('sub').value ; 
	var current_contact = document.getElementById('current_contact').value ;
	jQuery.ajax({
		type: "post",url: "admin-ajax.php",data: { action: 'update_data_action' , last_message_number : last_message_number  , _wpnonce , _wpnonce , sub : sub },
		beforeSend: function() {jQuery("#helloworld").fadeOut('fast');}, 
		success: function(rsp){ 
			

			var sessions = rsp.sessions;
			jQuery.each(sessions , function( session_key, session ){
				update_session_graphical(session_key,session);
			});	
					
			if(rsp.last_message_number !=  null ){
				// save the new last message number for the next requests 
				document.getElementById('last_message_number').value = rsp.last_message_number ; 

				// update the messages
				var msgs =	rsp.msgs;
				update_msgs_graphical(msgs);		
			}
		}
	});	
}
function update_msgs_graphical(msgs){
	jQuery.each(msgs , function( contact, contact_msgs ){
		move_to_top(contact);
		var new_recieved_msgs =  0;
		
		jQuery.each(contact_msgs.msgs , function( key, msg ){
			if(msg.fromMe != 1){
				new_recieved_msgs ++ ;
			}
		});
		
		var current_contact_unread_msgs =  document.getElementById(contact+"_new_msgs_counter").value  ; 
		current_contact_unread_msgs = parseInt(current_contact_unread_msgs) + parseInt(new_recieved_msgs);
		document.getElementById(contact+"_msg_counter").style.display = 'block'; 
		document.getElementById(contact+"_new_msgs_counter").value =  current_contact_unread_msgs
		document.getElementById(contact+"_msg_counter").innerHTML = current_contact_unread_msgs;
	
		// update the current msgs saced in the hidden inputs 	
		var old_contact_msgs = JSON.parse(get_msgs_from_hdn_inpt(contact));  // old messeges -> day -> day_msgs
		all_msgs =  jQuery.extend(old_contact_msgs, contact_msgs.msgs);
		document.getElementById(contact+'_hdn_inpt').value = "";
		document.getElementById(contact+'_hdn_inpt').value =  JSON.stringify(all_msgs);

		// update the last msg
		document.getElementById(contact+'_last_msg').innerHTML =  contact_msgs.last_msg ;
		document.getElementById(contact+'_last_msg').classList.add('font-weight-bold');	

		
		
		if(contact_msgs.last_msg_direction != false){ // comming msg      fas fa-arrow-up outcome-msg      fa-arrow-up 
			document.getElementById(contact + "_arrow_class").classList.add('outcome-msg');
			document.getElementById(contact + "_arrow_class").classList.add('fa-arrow-up');

			document.getElementById(contact + "_arrow_class").classList.remove('income-msg');
			document.getElementById(contact + "_arrow_class").classList.remove('fa-arrow-down');
		}else{
			document.getElementById(contact + "_arrow_class").classList.add('income-msg');
			document.getElementById(contact + "_arrow_class").classList.add('fa-arrow-down');

			document.getElementById(contact + "_arrow_class").classList.remove('outcome-msg');
			document.getElementById(contact + "_arrow_class").classList.remove('fa-arrow-up');
		}

		// change the content of the curren selected contect
		if(contact == document.getElementById('current_contact').value ){
			document.getElementById(contact+"_msg_counter").innerHTML = current_contact_unread_msgs ;
			if(current_contact_unread_msgs > 0){
				document.getElementById(contact+"_msg_counter").style.display = 'block'; 
			}
			show_msgs(contact,current_contact_unread_msgs) ;
		}
	});
}
jQuery( document ).ready( function( $ ) {
	reset_defults();
});

function reset_defults(){
	if(!document.getElementsByName("current_contact")){
		document.getElementById('current_contact').value =  0 ;
	}		
}
function move_to_top(id){
	const contact = document.getElementById(id); 
	const list = document.getElementById("chat-list"); 
	list.prepend(contact);
}
/****************************************************** Executive Code ******************************************************/
reset_defults();
jQuery(".chat-list-item").click(function(e){
	var current =  this.id;
	// show_msgs(current);
	// change the selected contact / mark the contact
	jQuery(".active").each(function() {
		document.getElementById(this.id).classList.remove('active');
	});	
	document.getElementById(current).classList.add('active');
});

setInterval(update_data,3*1000);
jQuery("#input").keydown(function(e){
	if(e.which === 13){
		jQuery("#msg_sending_btn").click();
		document.getElementById('input').value='';
		document.getElementById('input').value='';
	}
});
jQuery(".template").click(function(e){
	if(document.getElementById("input").disabled == false ){ // open
		document.getElementById('input').value = document.getElementById('input').value + " " +  this.getAttribute('data-content') ;
	}
});

jQuery( document ).ready( function( $ ) {
	// Uploading files
	var file_frame;
	var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
	var set_to_post_id = 000; // Set this

	jQuery('#upload_media_button').on('click', function( event ){
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			// Set the post ID to what we want
			file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
			// Open frame
			file_frame.open();
			return;
		} else {
			// Set the wp.media post id so the uploader grabs the ID we want when initialised
			wp.media.model.settings.post.id = set_to_post_id;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: 'اضف رسائل ميديا ',
			button: {
				text: 'اضف الميديا الي الرساله',
			},
			multiple: false // Set to true to allow multiple files to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			var attachs =  [];
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').toJSON();

			var input =  "";
			attachment.forEach(function(attach) {
				input +=  attach['id']+"&&&&&";
			});

			//title //url
			// Do something with attachment.id and/or attachment.url here
			$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
			$( '#files' ).val( input );
			// Restore the main post ID
			wp.media.model.settings.post.id = wp_media_post_id;
		});
			// Finally, open the modal
			file_frame.open();
	});
	// Restore the main ID when the add media button is pressed
	jQuery( 'a.add_media' ).on( 'click', function() {
		wp.media.model.settings.post.id = wp_media_post_id;
	});
});
jQuery(function(n) {
	jQuery('#messages').on('scroll', function() {
        if(jQuery(this).scrollTop() + jQuery(this).innerHeight() >= jQuery(this)[0].scrollHeight) {
			reset_msg_counters();
        }
    })
});
function reset_msg_counters(){
	var current_contact =  document.getElementById('current_contact').value ; 
	document.getElementById('float').style.display = 'none';
	document.getElementById(current_contact+'_new_msgs_counter').value = 0;
	document.getElementById(current_contact+'_msg_counter').style.display = 'none';
}
jQuery(".float").click(function(e){ // no changing
	scroll_to_last_msg();
});