<?php

function create_custom_msgs_table($sub) {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
    $table_name = get_msgs_table_name($sub);
    
    $sql = "CREATE TABLE $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT ,
        mobileNumber VARCHAR(20) NOT NULL ,
        textMessage VARCHAR(2000) NOT NULL ,
        isSent TINYINT(1) NOT NULL ,
        note VARCHAR(255) NOT NULL ,
        source TINYINT(1) NOT NULL ,
        status TINYINT(1) NOT NULL ,
        createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        updatedAt DATETIME NOT NULL ,
        PRIMARY KEY (id)
	)"; $charset_collate;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
function create_sessions_table($sub) {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
    $table_name = get_sessions_name($sub);
    
    $sql = "CREATE TABLE $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT ,
        mobileNumber VARCHAR(20) NOT NULL ,
        textMessage VARCHAR(2000) NOT NULL ,
        isSent TINYINT(1) NOT NULL ,
        note VARCHAR(255) NOT NULL ,
        source TINYINT(1) NOT NULL ,
        status TINYINT(1) NOT NULL ,
        createdAt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        updatedAt DATETIME NOT NULL ,
        PRIMARY KEY (id)
	)"; $charset_collate;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
function get_msgs_table_name($sub){
    return slug."_msgs_".$sub;
}
function get_sessions_name($sub){
    return slug."_sessions_".$sub;
}
function subs_option_field_name()
{
    return slug . "_subs";
}
function add_sub_to_blog($blog,$sub){
    switch_to_blog($blog);
    $option_name   =  subs_option_field_name();
    $current_value =  get_option( $option_name );

    if ( $current_value !== false ) {
        $new_value   = json_decode($current_value,true);
        $new_value[] = $sub;
        $new_value   = json_encode($new_value);
        update_option($option_name,$new_value);
    } else {     
        $deprecated = null;
        $autoload   = 'no';
        $new_value  = array($sub);
        $new_value  = json_encode($new_value);
        add_option($option_name,$new_value,$deprecated,$autoload);
    }    
    restore_current_blog();
}
function related_msgs_table(){
    $sub    =  get_page_sub_id();
    return get_msgs_table_name($sub);
}
function compose_messages_handler() {

    global $wpdb;
    $messege = $_REQUEST['messege'];
    $phones  = $_REQUEST['phones'];
    $lists   = $_REQUEST['lists'];
    $table   = $_REQUEST['table'];
    $user    = get_current_user_id();

    foreach ($phones as $phone) {
        $data = array(
            'textMessage' => $messege,
            'mobileNumber' => $phone,
            'isSent' => '1',
            'note' => ' ',
            'source' => '1',
            'status' => '1',
            'updatedAt' => '0000-00-00 00:00:00',
        );
        $wpdb->insert($table,$data);
    };
    wp_redirect( $_REQUEST['redirect'] );
    exit;
};