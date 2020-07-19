<form action='#' method='post' enctype="multipart/form-data" >
        <h2>Bulk Add Clients</h2>
        
        </br> </br>

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