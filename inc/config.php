<?php 
define('production', true);
define('domain', $_SERVER['SERVER_NAME'] );
define('scociet_port','3001');
define('societ_link' , 'http://'.domain.':'.scociet_port);
define('societ_action' , 'new_order');
define('session_duration' , 180 );  // in seconds
