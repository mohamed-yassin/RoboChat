<?php
/*
Plugin Name: RoboChat
Plugin URI: http://businessexp.net/
Description: Saas WhatsApp custom web solution , bulk messaging and bot builder.
Version: 1.1.1
Author: Business Experts
Author URI: http://businessexp.net/
Text Domain: robo
Domain Path: /inc/files/langs/
*/

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Constants
define('name', 'RoboChat');
define('slug', 'robo');
define('inc',plugin_dir_path(__FILE__).'inc/');
define('inc_url' , plugin_dir_url( __FILE__ ).'inc/' );
define('views',inc. 'views/');
define('files',inc. 'files/');
define('files_url',inc_url.'files/');
define('assets', inc_url.'views/assets/');
define('css',assets .'css/');
define('js',assets .'js/');
define('imgs',assets .'imgs/');
define('dflt_user_img',imgs .'dflt_user.png');
define('autoloader_path' , plugin_dir_path( __FILE__ ) . 'vendor/autoload.php' );
define('daily_msgs' , 3*60*24 );  //3msg/min * 60 min/hr * 24 hr/day
define('session_duration', 300);
define('allowed_msgs_per_min', 10);

$understrap_includes = array(
	'config',								// Main Config Settings
	'base_functions',						// Common Base Used function 
	'system_related_pages', 				// Create the ystem related pages
	'admin_pages_functions',    			// admin pages related 
	'dp', 									// dp related functions
	'subscriptions',						// subscriptions related functions
	'cpt', 									// regist custom post types 
	'plugins/extra_acf_fields/extra_fields',// regiest new acf components/fields
	'acf', 									// ACF ( advanced custom fields plugin ) fields 
	'enqueue', 								// enqueue  css & js files 
	'general_views',						// generate common views elements
	'sessions',								// Sessions Funtions
	'msgs',									// Messages related functions
	'real_time',							// real time related functions
	'chat_api', 							// related chat api functions
	'global_test',  						// just for testing
	'meta_bxs',								// custom meta boxes
	'crb',									// Caroon Fields : a plugin to unable create a custom fields
	'users',								// Users of the system
	'clients',								// clients/contacts
	'templates',							// Templates
	'chatbot',								// ChatBot
	'api',									// RoboChat Api End Points 
	'chatapi_based_api',					// RoboChat Api End Points Which depend on CHATAPI API
	'do_actions',							// THE MAIN CONTROLLER USING WP HOCK
	'do_actions_api_endpoints',				// THE MAIN CONTROLLER USIN

);
$error_counter = 0;
foreach ( $understrap_includes as $file ) {
	$path =  inc.$file.".php";
	if(is_file($path)){
		include_once($path);
	}else {
		$error_counter ++ ;
		echo "$error_counter  ::  Error when including one of the main core file not found :  $path </br>";
	}
}