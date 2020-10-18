<?php
define('production', true  ); 
if(!defined('production')){
    if(DOMAIN_CURRENT_SITE !=  'robochat' && DOMAIN_CURRENT_SITE != 'localhost'){
        define('production', true  );
    }else {
        define('production', false  );
    }
}