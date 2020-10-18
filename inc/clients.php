<?php
function roboChat_bulk_add_clients(  ) { 
	add_submenu_page( 'edit.php?post_type=client', __('Bulk Add Clients','robo'), __('Bulk Add Clients','robo'), 'manage_options', 'bulk_add_clients', 'roboChat_bulk_add_clients_page' );
}

function roboChat_bulk_add_clients_page(  ) {
    $result =  ""; 
    if($_POST){
        $csv =  $_FILES['clients_csv'];
        if($csv['type'] != 'application/octet-stream'){
            echo_notice ('please upload a CSV file' ,  'error');
        }else {
            $csv = array_map('str_getcsv', file($csv['tmp_name']));
            unset($csv[0]);
            $lists[] =  $_POST['list'];
            
            foreach ($csv as $key => $client) {
                $result .= $key == 1 ?  "<h3>Results</h3>" : "";
                $new_client_name       = $client[0];
                $phone                  = $client[1];
                $fields['first_name']   = $client[2];
                $fields['jop_title']    = $client[3];
                $fields['notes']        = $client[4];
                
                $created =  get_client_by_phone($phone ,$new_client_name , false , $lists , $fields  );
                $result .=  $created->post_title .  ' :: '.__('done','robo').' </br>' ;
            }
            $result .=  "_______________________________________________________________ ";
        }
    }
    echo $result ; 


    ?>
    <form action='#' method='post' enctype="multipart/form-data" >
        <h2><?= __('Bulk Add Clients','robo')?></h2>
        <tr>
            <th scope="row">
                <label for="blogname"><?= __('Download Sample','robo')?></label>
            </th>
            <td>
                <a href="<?= files_url.'bulk add clients file sample.csv' ?>"><?= __('Click Here','robo') ?></a>
            </td>
        </tr>
        </br> </br>
        <tr>
            <th scope="row">
                <label for="blogname"><?= __('Select the list','robo') ?></label>
            </th>
            <td>
                <select name="list">
                    <?php 
                        echo"<option value=''>".__('No Spicific List','robo')."</option>" ; 
                        $lists = get_terms([
                            'taxonomy' => 'list',
                            'hide_empty' => false,
                        ]);
                        foreach ((array)$lists as $list) {
                            echo"<option value='".$list->slug."'>".$list->name . "</option>" ; 
                        }
                    ?>
                </select>
            </td>
        </tr>
        </br> </br>
        <tr>
            <th scope="row">
                <label for="blogname"><?= __('Upload CSV file','robo') ?></label>
            </th>
            <td>
            <input type='file' name='clients_csv' required >
            </br>
            <?= __('1- Only CSV files accepted','robo'); ?> 
            </br>
            <?= __('2- To make it safe : please don\'t upload more than 500 client per time','robo') ?>
            </td>
        </tr>
        <?php submit_button(__('upload CSV','robo'));?>
    </form>
    <?php
}
function get_clients(){
    $args =  array(
        'post_type' =>'client',
        'status'    =>'publish'
    );
    $clients =  (array)get_posts($args);
    foreach ($clients as $key => $client) {
        $phone =  get_post_meta( $client->ID , 'phone', true );
        $client->phone =  $phone;
        $new_clients[$phone] =  $client;
    }
    return $new_clients;
}
function pure_phone($num){
    return  "00".(int) preg_replace('/\D/', '', $num); 
}
function filter_phone_number( $value, $post_id, $field ) {
    return pure_phone($value);  
}
function client_info($num){
    $num =  pure_phone($num);
    $args = array(
        'post_type'         => 'client',
        'posts_per_page'    => 1,
        'meta_query'        => array(
            array(
                'key'     => 'phone',
                'value'   => $num ,
                'compare' => '=',
            ),
        ),
    );
    $clients =  get_posts($args);
    if(is_array($clients) && count($clients) >  0){
        $return['name']       = $clients[0]->post_title  ;
        $return['img']        = get_the_post_thumbnail_url($clients[0]->ID ) ;
        $return['phone']      = $num ;
        $return['first_name'] = get_post_meta( $clients[0]->ID, 'first_name', true );
    }else {
        $return = FALSE;
    }
    return $return  ;
}
function get_client_by_phone($phone ,$new_client_name =  false , $return =  'id' ,$lists = array(), $fields = array() ){
    $phone =  pure_phone($phone);
    $args = array(
        'post_type'         => 'client',
        'posts_per_page'    => 1,
        'meta_query'        => array(
            array(
                'key'     => 'phone',
                'value'   => $phone ,
                'compare' => '=',
            ),
        ),
    );
    $clients = get_posts($args);
    if( isset($clients[0]) ){
        if($return == 'id'){
            return $clients[0]->ID;
        }else {
            return $clients[0];
        }
        return  $clients[0] ; 
    }elseif($new_client_name !=  false ){
        $my_client = array(
            'post_title'    => wp_strip_all_tags( $new_client_name  ),
            'post_type'     => 'client',
            'post_status'   => 'publish',
            'post_author'   => 1,
          );
        $id= wp_insert_post( $my_client );
        update_post_meta( $id , 'phone', $phone );
        if(count($fields) >  0 ){
            foreach ($fields as $key => $value) {
                update_post_meta( $id , $key , $value  );
            }
        }
        if(count($lists) >  0 ){
            foreach ($lists as $list_id) {
                if($list_id !=  '' ){
                    wp_set_object_terms( $id , $list_id , 'list');
                }
            }
        }
        if($return == 'id'){
            return $id;
        }else {
            return get_post($id);
        }
    }
}