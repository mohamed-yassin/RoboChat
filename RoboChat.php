<?php
/*
Plugin Name: RoboChat
Plugin URI: http://businessexp.net/
Description: Saas WhatsApp bulk messaging and bot builder
Version: 1.1.1
Author: Business Experts
Author URI: http://businessexp.net/
Text Domain: robo
*/

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Constants
define('name', 'RoboChat');
define('slug', 'robo');
define('inc',plugin_dir_path(__FILE__).'inc/');
define('views',inc. 'views/');
define('assets', plugin_dir_url( __FILE__ ).'inc/views/assets/');
define('css',assets .'css/');
define('js',assets .'js/');
define('imgs',assets .'imgs/');
define('dflt_user_img',imgs .'dflt_user.png');
define('autoloader_path' , plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );

$understrap_includes = array(
	'config',								// Main Config Settings
	'base_functions',						// common base used function 
	'admin_pages', 							// crete the main admin pages and control their content
	'admin_pages_functions',    			// admin pages related 
	'dp', 									// dp related functions
	'subscriptions',						// subscriptions related functions
	'cpt', 									// regist custom post types 
	'plugins/extra_acf_fields/hock/hock',	// regiest new acf components/fields
	'acf', 									// ACF ( advanced custom fields plugin ) fields 
	'enqueue', 								// enqueue  css & js files 
	'general_views',						// generate common views elements
	'sessions',								// Sessions Funtions
	'msgs',									// Messages related functions
	'real_time',							// real time related functions
	'chat_api', 							// related chat api functions
	'global_test',  						// just for testing
	'meta_bxs',								// custom meta boxes
	'users',								// Users
	
	'do_actions',							// THE MAIN CONTROLLER USING WP HOCK
);

foreach ( $understrap_includes as $file ) {
	$path =  inc.$file.".php";
	if(is_file($path)){
		include_once($path);
	}else {
		$error_counter ++ ;
		echo "$error_counter  ::  Error when including one of the main core file not found :  $path </br>";
	}
}