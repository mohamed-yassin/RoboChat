<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// define( 'WP_DEBUG', false );

// Start session 
session_start();

// Gloabal Functions
function pre($array , $title= ''){
	echo "<h3>$title</h3><pre>";
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
	$content = file_get_contents(inc."jsons/$file.json");
	return(json_decode($content, true));
};
function mk_json($fields,$json_file){
	$fp = fopen(inc."jsons/$json_file.json", 'w');
	fwrite($fp, json_encode($fields));
	fclose($fp);
};

function json_response($response){
	header('Content-Type: application/json');
	echo json_encode($response);
	die();
}
function not_null_auther_name($id){
	$user = get_userdata($id);
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
  }  
function request($request){
	// run request
}
function test_windows()
{
	if(is_admin()){
		function sample_admin_notice__success() { ?>
			<div class="notice notice-success is-dismissible">
				<?php include(inc."test.php"); ?>
			</div>
		<?php }
		add_action( 'admin_notices', 'sample_admin_notice__success' );
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
	/*
		field => array(
			'name',
			'type,
			'width'
			'note'
		)

	*/
	
	foreach ((array)$fields as $field) {
		$name 	= clear_value($field['name']);
		$type 	= clear_value($field['type']);
		$width	= clear_value($field['width']);
		$note	= clear_value($field['note']);
		$key	= $name;
		$val	= get_post_meta( $post->ID, $key , true );



		echo "<div class='rob-custom-field' ";
			echo '<h4>' . __($name, slug ) . ' </h4>';
			echo '<span> ' . __( $note , slug ) . '</span>';
			echo '<input class="rob-custom-field" type="text" name="first_name" value="' . esc_attr__( $first_name ) . '">';
		echo "</div>";

		if(isset($field['name'])){
			if ($field['type']) {
				# code...
			}
		}
	}
}