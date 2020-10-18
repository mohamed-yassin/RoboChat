<form action='#' method='post' enctype="multipart/form-data" >
        <h2><?= __('Bulk Add Clients','robo'); ?></h2>
        </br> </br>
        <tr>
            <th scope="row">
                <label for="blogname"><?= __('Select the list','robo'); ?></label>
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
            <?= __('1- Only csv files accepted','robo')?>
            </br>
            <?= __('2- To make it is safe : please do not upload more than 500 client per time','robo')?>
            </td>
        </tr>
        <?php submit_button(__('upload CSV','robo'));?>
    </form>