<?php
$current_user_availability =  array(
    // user : the wp system user
    // client : the phone number owner 
    
    '0' => 'reserved by another user',
    '1' => 'available for serving',
    '2' => 'the current user serving the client',




    '= 0'  => 'available for serving',
    '> 0' => 'id of the serving user',
);


$messege_with_signature_status = array(
    '0' => "not available for messaging",
    '1' => "didn't send any messege yet in this session",
    '2' => "he sent his segnuture in the first messege in this session",
);