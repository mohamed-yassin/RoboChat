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
define('production', false);
define('name', 'RoboChat');
define('slug', 'robo');
define('domain', 'localhost');
define('inc',plugin_dir_path(__FILE__).'inc/');
define('views',inc. 'views/');
define( 'assets', plugin_dir_url( __FILE__ ).'inc/views/assets/');
define('css',assets .'css/');
define('js',assets .'js/');
define('imgs',assets .'imgs/');
define('dflt_user_img',imgs .'dflt_user.png');


$understrap_includes = array(
	'base_functions',
	'http_requests',
	'super_admin_pages',
	'admin_pages',
	'admin_pages_functions',
	'admin_columns',
	'plugin_functions',
	'plugins/extra_acf_fields/hock/hock',
	'api',
	'dp',
	'subscriptions',
	'cpt',
	'acf',
	'do_actions',
	'enqueue',
	'general_views_functions',
	'global_test',
	
	//'carbon_fields/carbon_fields',
	//'crb',
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