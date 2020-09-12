<?php
if(DOMAIN_CURRENT_SITE !=  'robochat'){
    define('production', true  );
}else {
    define('production', false  );
}