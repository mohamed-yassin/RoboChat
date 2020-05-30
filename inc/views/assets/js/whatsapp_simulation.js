function show_msgs(id) {
	var msgs_box = 'messages';
	document.getElementById("name").innerHTML  =  document.getElementById(id+"_name").innerHTML ;
	document.getElementById("pic").src = document.getElementById(id+"_pic").src;
	document.getElementById(msgs_box).innerHTML = '';
	const contact_msgs = JSON.parse(get_msgs_from_hdn_inpt(id));
	for (const [day, day_msgs] of Object.entries(contact_msgs)) {
		jQuery("#messages").append("<div class='mx-auto my-2 bg-primary text-white small py-1 px-2 rounded'>"+  day + " </div>");
		var day_msgs_array = obj_into_array(day_msgs);

		for (const [msg_gen_key, msg_gen] of Object.entries(day_msgs_array)) {
			// var day_msgs_array = obj_into_array(day_msgs);
			real_msg = msg_gen[1] ;
			console.log(real_msg);
			//var msg_div_html = msg_div_html(real_msg.body,real_msg.time,real_msg.fromme);
			append_msg_html(real_msg.body, real_msg.real_time , real_msg.fromMe);
		}
	}
	document.getElementById('current_contact').value =  id ;

	handle_send_msg_ability();
};
function append_msg_html(msg,date,fromme) {
	if(fromme == true){
		var class_name =  "align-self-end self ";
	}else{
		var class_name =  "align-self-start ";
	};
	msg_div = "<div class='";
	msg_div += class_name ;
	msg_div += " p-1 my-1 mx-3 rounded bg-white shadow-sm message-item'><div class='d-flex flex-row'><div class='body m-1 mr-2'>";
	msg_div += msg;
	msg_div += "</div><div class='time ml-auto small text-right flex-shrink-0 align-self-end text-muted'>";
	msg_div += date ;
	msg_div += "</div></div></div>";
	jQuery("#messages").append(msg_div);
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

function handle_send_msg_ability() {
	var current_contact =  document.getElementById('current_contact').value ; 
	if(current_contact !=  0){
		var current_contact_status = document.getElementById(current_contact +"_available").value  ;
		if(current_contact_status == 2 ){
			document.getElementById("input").disabled = false ;
	
			document.getElementById("lock").classList.add('fa-lock-open');
			document.getElementById("lock").classList.remove('fa-lock');
		}else{
			document.getElementById("input").disabled = true ;
	
			document.getElementById("lock").classList.add('fa-lock');
			document.getElementById("lock").classList.remove('fa-lock-open');
		}
		
	}
}
function open_send_msg_ability() {
	var current_contact =  document.getElementById('current_contact').value ; 
	if(current_contact !=  0){
		var current_user_availability = document.getElementById(current_contact +"_available").value ;
		if(current_user_availability ==  1){ // if not fot another customer services   comments  fa-sliders-h
			document.getElementById(current_contact +"_available").value = 2  ; // open/assign it for the current customer services
	
			document.getElementById("input").disabled = false ;
			document.getElementById("lock").classList.add('fa-lock-open');
			document.getElementById("lock").classList.remove('fa-lock');
	
			document.getElementById(current_contact + "_available_icon").classList.add('fa-comments'); 
			document.getElementById(current_contact + "_available_icon").classList.add('income-msg');
		} else if (current_user_availability ==  2){
			document.getElementById(current_contact +"_available").value = 1  ; // close it for the current customer services
	
			document.getElementById("input").disabled = true ;
			document.getElementById("lock").classList.add('fa-lock');
			document.getElementById("lock").classList.remove('fa-lock-open');
	
			document.getElementById(current_contact + "_available_icon").classList.remove('fa-comments'); 
			document.getElementById(current_contact + "_available_icon").classList.remove('income-msg');
	
		}
	
	}
}
function sendMessage (){
	var today = new Date();
	var time = today.getHours() + ":" + today.getMinutes();
	var msg =  document.getElementById('input').value ; 
	document.getElementById('input').value  = '';
	
	
	setTimeout(function () {
		var counter  =  parseInt(document.getElementById("msgs_counter").innerHTML); 
		counter =  counter - 1 ; 
		document.getElementById("msgs_counter").innerHTML = counter;
		append_msg_html(msg,time,1);
	}, 3500);
	

}