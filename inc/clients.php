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
            
            foreach ($csv as $key => $contact) {
                $result .= $key == 1 ?  "<h3>Results</h3>" : "";
                $new_contact_name       = $contact[0];
                $phone                  = $contact[1];
                $fields['first_name']   = $contact[2];
                $fields['jop_title']    = $contact[3];
                $fields['notes']        = $contact[4];
                
                $created =  get_contact_by_phone($phone ,$new_contact_name , false , $lists , $fields  );
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
            <?= __('1- Only csv files accepted','robo'); ?> 
            </br>
            <?= __('2- To make it safe : please don\'t upload more than 500 contact per time','robo') ?>
            </td>
        </tr>
        <?php submit_button(__('upload cvs','robo'));?>
    </form>
    <?php
}