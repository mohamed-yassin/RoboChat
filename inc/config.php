<?php 
define('production', true );

if(production ==  true){
    define('domain', 'devbx.udh.sa' );
}else {
    define('domain', 'localhost' );
}
define('scociet_port','3001');
define('societ_link' , 'http://'.domain.':'.scociet_port);
define('societ_action' , 'new_order');
define('session_duration' , 180 );  // in seconds
