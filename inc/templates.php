<?php
function get_templates(){
    $args =  array(
        'post_type' =>'template',
    ); 
    return (array)get_posts($args);
}