<?php
// Start session 
session_start();

// Gloabal Functions
function pre($array , $title= ''){
	echo $title != '' ?  "<h3>$title</h3>" :  '';
	echo "<pre>" ;
	print_r ($array);
	echo "</pre></br>";
};
function text( $text= NULL){
	if ($text == NULL) {
		echo "<h6>A long text to be shown in places when you can't see results so perhaps you will need to make a standard check . You can serchh for me in the page with sssss .</h6>";
	}else {
		echo "<h3>$text</h3>";
	}
};
function is_local(){
	if ($_SERVER['REMOTE_ADDR']=='127.0.0.1' || $_SERVER['REMOTE_ADDR']=='::1') {
		return TRUE;
	}
};
function get_json($file){
	$content = file_get_contents($file);
	return(json_decode($content, true));
};
function mk_json($data,$file){
	$fp = fopen($file,'w');
	fwrite($fp, json_encode($data));
	fclose($fp);
};

function json_response($response){
	header('Content-Type: application/json');
	echo json_encode($response);
	die();
}
function not_null_auther_name($id){
	$user = get_userdata($id);
	if(is_object($user)){
		if ($user->nickname   != '') {
			$user_name = $user->nickname ;
		}elseif ($user->display_name  != '') {
			$user_name = $user->display_name  ;
		}elseif ($user->first_name != '') {
			$user_name = $user->first_name ;
		}else {
			$user_name = $user->user_login ;
		}
		return $user_name ;
	}else {
		return ;
	}
  }  
function request($request){
	// run request
}
function test_windows()
{
	if(is_admin()){
		function sample_admin_notice_success() { ?>
			<div class="notice notice-success is-dismissible">
				<?php include(inc."test.php"); ?>
			</div>
		<?php }
		add_action( 'admin_notices', 'sample_admin_notice_success' );
	}else {
		include(inc."test.php");
	}	
}
if( production == false){ test_windows();}    // just testing purpose 

function test_post($title , $body = ''){
	$my_test_post = array(
        'post_title'    => $title ,
        'post_content'  => array_to_text($body) ,
        'post_status'   => 'draft',
        'post_author'   => 1,
        );
    return wp_insert_post( $my_test_post );
}
function array_to_text($array,$title=''){
	$return = $title == '' ? $title :  "<h3>$title</h3> ";
	$return .= json_encode($array) ;
	return  $return ;
}
function view($view,$data = array()){
	foreach ($data as $var => $value) {
		$$var =  $value; 	// make a variable for each data
	}
	$view =  views.$view.".php";
	if(! include_once($view)){
		$error_counter ++ ;
		echo "$error_counter  ::  Error when including the view  $view </br>";
	}
}
function clear_value($value = ''){
	return $value;
}
function render_custom_fields($fields){
	foreach ((array)$fields as $field) {
		$name 	= clear_value($field['name']);
		$type 	= clear_value($field['type']);
		$width	= clear_value($field['width']);
		$note	= clear_value($field['note']);
		$key	= $name;
		$val	= get_post_meta( $post->ID, $key , true );
		echo "<div class='rob-custom-field' ";
			echo '<h4>' . __($name,'robo') . ' </h4>';
			echo '<span> ' . __( $note,'robo') . '</span>';
			echo '<input class="rob-custom-field" type="text" name="first_name" value="' . esc_attr__($first_name,'robo') . '">';
		echo "</div>";
		if(isset($field['name'])){
			if ($field['type']) {
				# code...
			}
		}
	}
}

function token($string){
	$string =  slug."@".$string."@"."robochat";
	return hash('md5', $string );
}
function token_time_based($string ,$unit = 'i' , $valid_for = 1, $hash =  'md5'){
	//units y,m,d,h,i,s
	//$time = time();
};
function echo_notice($text , $class =  ''){
	// $class :  error  , success 
	echo  '
		<div class="notice notice-'.$class.' is-dismissible">'.
		$text
		.'<button type="button" class="notice-dismiss">
				<span class="screen-reader-text">Dismiss this notice.</span>
			</button>
		</div>
		';
}
function file_url($file){
	if(production != true ) {
	  return 'https://cdn.pixabay.com/user/2014/05/07/00-10-34-2_250x250.jpg' ; 
	}elseif ((int)$file > 0) {
	  return  wp_get_attachment_url($file) ;
	}else {
	  return $file ;
	}
}
function robo_load_text_domain() {
	$relative_path =  dirname( plugin_basename( __FILE__ ) )."/files/langs/";
    load_plugin_textdomain( 'robo',false,$relative_path);
}
function filter_fileds($fields){
	if(!is_array($fields) ||  count($fields) == 0){
		return __('No fields added');
	}
	$html =  '<h5>'.__('Search Messages').'</h5>';
	$html .= '<table>';
	foreach ($fields as $key => $field) {
		$label 	=  isset($field['label']) ? $field['label'] : '';
		$name 	=  isset($field['name']) ?  $field['name'] : '';
		$type 	=  isset($field['type']) ?  $field['type'] : '';
		$value 	=  isset($field['value']) ? $field['value'] : (isset($_REQUEST[$name]) ?  $_REQUEST[$name]:'');

		$html .= '<tr>';
		$html .= "<td><label>$label</label><td>";
		if($type == 'text'){
			$html .= "<td><input type='$type' name='$name' value='$value'><td>";
		}elseif ($type == 'date') {
			//
		}
		$html .= filter_fileds_operators($name,$type);
		$html .= '</tr>';
	}
	$html .= '</table>';
	echo $html ;
}
function filter_fileds_operators($name,$type){
	$html = '';
	if($type == 'date'){
		$filters =  array('from','to');
		foreach ($filters as $filter) {
			$key =  $name."_".$filter;
			$value =  isset($_REQUEST[$key])? $_REQUEST[$key] : '';
			$html .= "<td><label>$filter</label><input type='$type' name='$key' value='$value'><td>";
		}
		return $html ;
	}











	$name= $name.'_filter';
	$f1_value =  isset($_REQUEST[$name])? $_REQUEST[$name] : ''; 

	if($type ==  'text'){
		$ops=  array('like','=',);
	}elseif ($type ==  'number') {
		$ops=  array('like','=','>','<');
	}
	$html = "<select name='$name'>";
	$html .= "<option value='0'></option>";

	foreach ($ops as $op) {
		$selected =  $op == $f1_value ? ' selected ' : '';
		if($op == 'like'){
			$html .= "<option value='like'$selected>".__('Like')."</like>";
		}elseif ($op == '=') {
			$html .= "<option value='='$selected>".__('Equal')."</like>";
		}elseif ($op == '>') {
			$html .= "<option value='>'$selected>".__('Greater than')."</like>";
		}elseif ($op == '<') {
			$html .= "<option value='<'$selected>".__('Less than')."</like>";
		}
	}
	$html .= "</select>";
	return  $html; 
}