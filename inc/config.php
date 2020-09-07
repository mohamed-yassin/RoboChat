<?php 

define('production', true  );

if(production ==  true){
    define('domain', 'devbx.udh.sa' );
}else {
    define('domain', 'robochat' );
}