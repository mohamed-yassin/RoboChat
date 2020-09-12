<?php 
    function has_robo_permission($process){
        if(is_super_admin()){
            return  true ;
        }
        $user  = get_current_user_id();
        $meta_value =  get_the_author_meta( $process , $user ) ; 
        return $meta_value ;  
        if($meta_value){
            return true ;
        }
    }