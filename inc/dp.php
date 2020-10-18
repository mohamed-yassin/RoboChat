<?php
function create_queried_msgs_table($sub) {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
    $table_name = get_msgs_table_name($sub);
    
    $sql = "CREATE TABLE $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT ,
        mobile_number VARCHAR(20) NOT NULL ,
        msg_body VARCHAR(2000) NOT NULL ,
        msg_type VARCHAR(2000) NOT NULL ,
        msg_caption VARCHAR(2000) NOT NULL ,
        is_sent TINYINT(1) NOT NULL ,
        note VARCHAR(255) NOT NULL ,
        source TINYINT(1) NOT NULL ,
        status TINYINT(1) NOT NULL ,
        user INT(11) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
        updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
	)"; $charset_collate;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
function create_msgs_archive_table($sub) {
	global $wpdb;
	$charset_collate = $wpdb->get_charset_collate();
    $table_name = get_msgs_table_name($sub,'archive');
    
    $sql = "CREATE TABLE $table_name (
        robo_id INT(11) NOT NULL AUTO_INCREMENT ,
        id              VARCHAR(100)    NOT NULL ,
        body            TEXT   NOT NULL ,
        fromMe          VARCHAR(10),
        self            VARCHAR(10),
        isForwarded     VARCHAR(10),
        author          VARCHAR(20)   NOT NULL ,
        time            VARCHAR(20)   NOT NULL ,
        chatId          VARCHAR(100)   NOT NULL ,
        messageNumber   VARCHAR(100)   NOT NULL ,        
        type            VARCHAR(10)    NOT NULL ,
        senderName      VARCHAR(100)   NOT NULL ,
        quotedMsgBody   TEXT,
        quotedMsgId     VARCHAR(100)   NOT NULL ,
        chatName        VARCHAR(100)   NOT NULL ,
        caption         TEXT,
        UNIQUE(id),
        PRIMARY KEY (robo_id)
	)"; $charset_collate;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}
function get_msgs_table_name($sub,$table='queried'){
    return $table == 'queried' ?  slug."_msgs_".$sub : slug."_msgs_archive_".$sub;
}
function get_table_name($sub,$table){
    return slug."_".$table."_".$sub;
}
function get_sessions_name($sub){
    return slug."_sessions_".$sub;
}
function subs_option_field_name()
{
    return slug . "_subs";
}
function subs_option_field_value(){
    return get_option(subs_option_field_name());
}
function subs_option_field_array(){
    return json_decode(subs_option_field_value() , true);
}
function add_sub_to_blog($blog,$sub){
    switch_to_blog($blog);
    $option_name   =  subs_option_field_name();
    $current_value =  get_option( $option_name );

    $slug =  rand_sub_code($sub) ; 

    if ( $current_value !== false ) {
        $new_value   = json_decode($current_value,true);
        $new_value[$slug] = array('id'=> $sub ,'slug' => $slug);
        $new_value = json_encode($new_value);
        update_option($option_name,$new_value);
    }else {     
        $deprecated = null;
        $autoload   = 'no';
        $new_value[$slug]  = array('id'=> $sub ,'slug' => $slug );
        $new_value  = json_encode($new_value);
        add_option($option_name,$new_value,$deprecated,$autoload);
    }    
    restore_current_blog();
    return $slug; 
}
function related_msgs_table($table=''){
    $sub    =  get_page_sub_id();
    return get_msgs_table_name($sub,$table);
}
function related_sub_id($slug)
{
    $subs =  subs_option_field_array();
    foreach ($subs as $key => $sub) {
        if(is_array($sub) &&  $sub['slug'] ==  $slug ){
            return  $sub['id'];
        }
    }
    return ;
}
function dp_insert_multi_rows($row_arrays = array(), $wp_table_name) {
    global $wpdb;
    $wp_table_name = esc_sql($wp_table_name);
    // Setup arrays for Actual Values, and Placeholders
    $values = array();
    $place_holders = array();
    $query = "";
    $query_columns = "";

    $query .= "INSERT INTO {$wp_table_name} (";

            foreach($row_arrays as $count => $row_array)
            {

                foreach($row_array as $key => $value) {

                    if($count == 0) {
                        if($query_columns) {
                        $query_columns .= ",".$key."";
                        } else {
                        $query_columns .= "".$key."";
                        }
                    }

                    $values[] =  $value;

                    if(is_numeric($value)) {
                        if(isset($place_holders[$count])) {
                        $place_holders[$count] .= ", '%d'";
                        } else {
                        $place_holders[$count] .= "( '%d'";
                        }
                    } else {
                        if(isset($place_holders[$count])) {
                        $place_holders[$count] .= ", '%s'";
                        } else {
                        $place_holders[$count] .= "( '%s'";
                        }
                    }
                }
                        // mind closing the GAP
                        $place_holders[$count] .= ")";
            }

    $query .= " $query_columns ) VALUES ";

    $query .= implode(', ', $place_holders);

    if($wpdb->query($wpdb->prepare($query, $values))){
        return true;
    } else {
        return false;
    }
}
function update_msgs_archive($msgs=array())
{
    $table = related_msgs_table('archieve');
    $msgs  = get_json(files.'messages.json');

    global $wpdb;
    $keys =  array(
        'id', 'body', 'fromMe', 'self', 'isForwarded', 'author', 'time', 'chatId', 'messageNumber', 'type', 'senderName', 'quotedMsgBody', 'quotedMsgId', 'chatName', 'caption'
    );
    $last_index =  count($keys) - 1;

    foreach ($msgs as $msg) {
        $values = '';
        foreach ($keys as $index => $key) {
            $seperator     = $index != $last_index ? ' , ' :  ' ' ;
            $current_value = isset($msg[$key]) ? $msg[$key] : '';
            $current_value = "'".$current_value."'".$seperator;
            $values .= $current_value;
        }
        $sql = "INSERT INTO $table (id, body, fromMe, self, isForwarded, author, time, chatId, messageNumber, type, senderName, quotedMsgBody, quotedMsgId, chatName, caption) VALUES ($values);";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
} 