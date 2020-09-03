<?php 

if(WP_DEBUG !=  true){
    define('production', true  );
    define('domain', 'devbx.udh.sa' );
}else {
    define('production', false  );
    define('domain', 'robochat' );
}