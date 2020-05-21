<?php


foreach( $blog_ids as $blog_id ){
    switch_to_blog( $blog_id );
    //Do stuff
    restore_current_blog();
}

$blogs =  wp_get_sites();
