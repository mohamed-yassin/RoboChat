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
			real_msg = msg_gen[1] ;
			append_msg_html(real_msg.body, real_msg.real_time , real_msg.fromMe);
		}
	}
	document.getElementById('current_contact').value =  id ;

	handle_send_msg_ability();
};
function append_msg_html(msg,date,code) {

	if(code  == 0){ // recieved
		var class_name =  "align-self-start ";
		var status =  "";
	}else if(code  == 1){ // sent succesfully
		var class_name =  "align-self-end self ";
		var status =  " " + "<i class='fas fa-check  income-msg'></i>" + " ";
	}else if(code  == 2){ // msgs balance ranout
		var class_name =  "align-self-end self ";
		var status =  " " + "<i class='fas fa-times-circle  outcome-msg'></i>" + " ";
	}else if(code  == 3){ // chat api error
		var class_name =  "align-self-end self ";
		var status =  " " + "<i class='fas fa-times-circle  outcome-msg'></i>" + " ";
	}

	msg_div = "<div class='";
	msg_div += class_name ;
	msg_div += " p-1 my-1 mx-3 rounded bg-white shadow-sm message-item'><div class='d-flex flex-row'><div class='body m-1 mr-2'>";
	msg_div += status; 
	msg_div += msg; 
	msg_div += "</div><div class='time ml-auto small text-right flex-shrink-0 align-self-end text-muted'>";
	msg_div += date ;
	msg_div += "</div></div></div>";

	jQuery("#messages").append(msg_div);
	jQuery('#messages').stop().animate({
		scrollTop: jQuery('#messages')[0].scrollHeight
	  }, 800);
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
		if(current_contact_status == 2 ){ // if it allready available for the user : close it 
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
function update_contact_reservation(current_contact,new_current_user_availability){

	jQuery.ajax({
		type: "post",url: "admin-ajax.php",data: { action: 'update_session_action' , contact : current_contact , availability :  new_current_user_availability   },
		beforeSend: function() {jQuery("#helloworld").fadeOut('fast');}, 
		success: function(rsp){ 
			//console.log("this is the emited rsp" +  rsp);
		}
	});	
}
function open_send_msg_ability(current_contact ,  current_user_availability  , current_user  ) {
	if(current_contact !=  0){
		
		
		//update_sessions(current_contact,new_current_user_availability);
	}
}

function sendMessage (){
	var msg   = document.getElementById('input').value ;
	document.getElementById('input').value  = '';

	if(msg.length  > 0 ){
		var today = new Date();
		var time  = today.getHours() + ":" + today.getMinutes();
		var num   = document.getElementById('current_contact').value ; 
		var sub   = document.getElementById('current_sub').value ; 
		var sign  = document.getElementById(num+'_signature').value ; 
		jQuery.ajax({
			type: "post",url: "admin-ajax.php",data: { action: 'send_instant_msg_action' , msg :  msg , num : num , sub : sub , sign :  sign  },
			beforeSend: function() {jQuery("#helloworld").fadeOut('fast');}, 
			success: function(rsp){ 
	
				rsp = JSON.parse(rsp);
				var status = rsp.status;
				msg        = rsp.msg; 
				if(status == 1){ // send successfully 
					document.getElementById("msgs_counter").innerHTML = rsp.balance;
				}else if(status == 2){ // if daily msg balance is ranout
					document.getElementById("msgs_counter").innerHTML = "لقد نفذ الرصيد يرجي التجديد";
				}
				append_msg_html(msg,time,status);
				document.getElementById(num + "_signature").value = 2;
			}
		});	
	}else{
		alert('رساله فارغه');
	}
}
function update_session(current_user,sub){
	var current_contact =  document.getElementById('current_contact').value ;
	if(current_contact !=  0){ // if their is a selected contact 
		var current_contact_status = document.getElementById(current_contact +"_available").value  ;
		jQuery.ajax({
			type: "post",url: "admin-ajax.php",data: { action: 'update_session_action' , contact : current_contact , user : current_user , sub : sub  },
			beforeSend: function() {jQuery("#helloworld").fadeOut('fast');}, 
			success: function(rsp){ 
				//console.log("this is the emited rsp" +  rsp);
			}
		});
	}
}
jQuery("#input").keydown(function(e){
	if(e.which === 13){
		jQuery("#msg_sending_btn").click();
	}
});
function update_session_graphical(current_contact,serving_user,current_user){
	if(serving_user == current_user){
		document.getElementById(current_contact +"_available").value = 2  ;
		document.getElementById("input").disabled = false ;
		document.getElementById("lock").classList.add('fa-lock-open');
		document.getElementById("lock").classList.remove('fa-lock');
		document.getElementById(current_contact + "_available_icon").classList.add('fa-comments'); 
		document.getElementById(current_contact + "_available_icon").classList.add('income-msg');
		document.getElementById(current_contact + "_signature").value = 1;
	}else {
		document.getElementById("input").disabled = true ;
		document.getElementById("lock").classList.add('fa-lock');
		document.getElementById("lock").classList.remove('fa-lock-open');
		document.getElementById(current_contact + "_available_icon").classList.remove('fa-comments'); 
		document.getElementById(current_contact + "_available_icon").classList.remove('income-msg');
		document.getElementById(current_contact + "_signature").value = 0  ;
		if(serving_user == 0){
			document.getElementById(current_contact +"_available").value = 1  ;
		}else{
			document.getElementById(current_contact +"_available").value = 0  ;
			document.getElementById(current_contact + "_available_icon").classList.add('fa-comments'); 
			document.getElementById(current_contact + "_available_icon").classList.add('outcome-msg');
		}
	}
};
jQuery(".template").click(function(e){
	if(document.getElementById("input").disabled == false ){ // open
		document.getElementById('input').value = document.getElementById('input').value + " " +  this.getAttribute('data-content') ;
	}
});