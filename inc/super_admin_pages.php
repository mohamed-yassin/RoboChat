<?php 
add_action("network_admin_menu", "misha_new_menu_items");
function misha_new_menu_items() {
 
	add_submenu_page(
		'themes.php', // Parent element
		'Settings Page 1', // Text in browser title bar
		'Settings Page 1', // Text to be displayed in the menu.
		'manage_options', // Capability
		'settings-page-1', // Page slug, will be displayed in URL
		'misha_settings_page_1' // Callback function which displays the page
	);
 
	add_menu_page(
		'RoboChat',
		'RoboChat',
		'manage_options',
		'settings-page-2',
		'misha_settings_page_1',
 		'', // Icon
		100 // Position of the menu item in the menu.
	);
}
function misha_settings_page_1(){
	echo "<h1>RoboChat Super Admin</h1>";
}