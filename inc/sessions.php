<?php
function available_sessions($sub){
    $sessions   = get_current_sessions($sub);
    $sessions   = is_array($sessions) ?  $sessions :  array(); 
    $date 		= time(); 
	$user		= get_current_user_id(); 
	foreach ($sessions as $key => $session) {
		$time_def =  $date - $session[1];
		if($time_def >  session_duration ){                // if the user didn't served with the client for a long time , free the client 
			unset($sessions[$key]);
		}else {
			$sessions[$key]['available'] = $session[0] == $user ?  2 : 0 ;
			$sessions[$key]['available_icon']     = $session[0] == $user ?  'fa-comments income-msg' : 'fa-comments outcome-msg' ;	
		}
	}
	return $sessions ;
}
function session_file_path($sub){
	return plugin_dir_path( __FILE__ ).'../../../sessions/'.$sub.".json"; 
}
function get_current_sessions($sub){
	$path 		= session_file_path($sub);
	$sessions 	= @file_get_contents($path);
	return      json_decode($sessions,TRUE);
}
function update_current_sessions($sub,$sessions){
	$path 		= session_file_path($sub);
    $sessions   = json_encode($sessions);
    file_put_contents($path, $sessions);
    return $sessions ; 
}

