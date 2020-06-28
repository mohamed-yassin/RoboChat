<?php 
    function has_robo_permission($process){
        $user  = get_current_user_id();
        if(get_the_author_meta( $process , $user ) ){
            return true ;
        }
    }

