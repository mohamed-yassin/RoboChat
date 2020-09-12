<?php
function available_sessions($sub){
	$sessions = sessions_for_current_user($sub);
	foreach ((array)$sessions as $key => $session) {
		$sessions[$key]['available'] = $session[0] ;

		if($session[0] ==  0){
			$sessions[$key]['available_icon'] = 'fa-comments outcome-msg';
		}elseif ($session[0] == 1) {
			$sessions[$key]['available_icon'] = '';
		}elseif ($session[0] == 2) {
			$sessions[$key]['available_icon'] = 'fa-comments income-msg';
		}
	}
	return $sessions ;
}
function session_file_path($sub){
	return plugin_dir_path( __FILE__ ).'../../../sessions/'.$sub.".json"; 
}
function get_current_sessions($sub){
	$path 		= session_file_path($sub);

	if (!file_exists($path)) {
		touch($path);
	};
	$sessions 	= file_get_contents($path);
	return      json_decode($sessions,TRUE);
}
function sessions_for_current_user($sub){
	$sessions = get_current_sessions($sub);
	$user_id  = get_current_user_id();  
    $time 	  = time(); 

	foreach ((array)$sessions as $key => $session) {
		$time_def =  $time - $session[1];
		if($sessions[$key][0] == $user_id && $time_def <  session_duration ){
			$sessions[$key][0] = 2 ;    		// 2 : the current user serve the client
		}elseif ($sessions[$key][0] == 0 || $time_def >  session_duration ) {

			$sessions[$key][0] = 1 ;			// 1 : free client
		}else {
			$sessions[$key][0] = 0 ;			// 0 : reserved by another user
		}
	}
	return $sessions ;
}

function update_current_sessions($sub,$sessions){
	$path 		= session_file_path($sub);
    $sessions   = json_encode($sessions);
    file_put_contents($path, $sessions);
    return $sessions ; 
}