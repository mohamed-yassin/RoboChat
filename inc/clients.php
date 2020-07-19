<?php


function roboChat_bulk_add_clients(  ) { 

	add_submenu_page( 'edit.php?post_type=client', 'Bulk Add Clients', 'Bulk Add Clients', 'manage_options', 'bulk_add_clients', 'roboChat_bulk_add_clients_page' );

}

function roboChat_bulk_add_clients_page(  ) { 
    if($_POST){
        $csv =  $_FILES['clients_csv'];
        if($csv['type'] != 'application/octet-stream'){
            echo_notice ('please upload a CSV file' ,  'error');
        }else {
            $csv['name'] =  time().'_'.$csv['name'];
            $distination  =  files.'csv/'.$csv['name'] ;
            move_uploaded_file($csv['tmp_name'] , $distination  );

            $csv = array_map('str_getcsv', file($distination));
            unset($csv[0]);
            $lists[] =  $_POST['list'];
            
            foreach ($csv as $key => $contact) {
                $new_contact_name       = $contact[0];
                $phone                  = $contact[1];
                $fields['first_name']   = $contact[2];
                $fields['jop_title']    = $contact[3];
                $fields['notes']        = $contact[4];
                
                //echo  ($key+1) . " " . $fields['notes'] .  "</br>";  
                //echo  ($key+1) .  "  " .  $new_contact_name  .  "  " .  $phone  .  "  " .  $fields['first_name']  .  "  " .  $fields['jop_title'] . "  " . $fields['notes'] . "</br>";  
                $created =  get_contact_by_phone($phone ,$new_contact_name , false , $lists , $fields  );
                echo($created->post_title .  ':: done </br>');
            }
        }

    }



    ?>
    <form action='#' method='post' enctype="multipart/form-data" >
        <h2>Bulk Add Clients</h2>
        
        
        <tr>
            <th scope="row">
                <label for="blogname">Select the list</label>
            </th>
            <td>
                <select name="list">
                    <?php 
                        echo"<option value=''>No Spicific List</option>" ; 
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
                <label for="blogname">Upload CSV file</label>
            </th>
            <td>
            <input type='file' name='clients_csv' required >
            </br>
            1- Only csv files accepted 
            </br>
            2- To make it's safe : please don't upload more than 200 contact per time
            </td>
        </tr>





        <?php submit_button('upload cvs');?>
    </form>
    <?php
}