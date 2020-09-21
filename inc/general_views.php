<?php
function render_table($headers = array() ,$body,$footers  =  false){
    echo '<table class="wp-list-table widefat fixed striped posts">';
    if(is_array($headers)){ ?>
        <thead>
            <tr>
                <?php 
                    foreach ($headers as $head) { 
                        echo "<th scope='col' id='$head' class='manage-column column-$head'>$head</th>";
                    }
                ?>
            </tr>
        </thead>
    <?php };
    if(is_array($body)){ ?>
        <thead>
                <?php 
                    foreach ($body as $row) { 
                        echo  "<tr>";
                        foreach ($row as $row_item_key => $row_item) {
                            echo "<td scope='col' id='$row_item_key' class='manage-column column-$row_item_key'>$row_item</td>";
                        }
                        echo  "</tr>";

                    }
                ?>
        </thead>
    <?php };
        if($footers ==  true ){ ?>
        	<thead>
                <tr>
                    <?php 
                        foreach ($headers as $header) { 
                            echo "<td scope='col' id='$header' class='manage-column column-$header'>$header</td>";
                        }
                    ?>
                </tr>
            </thead>
    <?php }
    echo "</table>";
}
function whatsapp_messeges_table($api,$token)
{
    $messeges =  whatsapp_messeges($api,$token) ;
    echo '<br><h2>'.__('Show Messages','robo').'</h2><table class="wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <th scope="col" id="author" class="manage-column column-author">'.__('Message Text','robo').'</th>
                    <th scope="col" id="categories" class="manage-column column-categories">'.__('Sender','robo').'</th>
                    <th scope="col" id="tags" class="manage-column column-tags">'.__('Chat Name','robo').'</th>
                </tr>
            </thead>
            <tbody id="the-list">';
            foreach($messeges as $key =>$messege){
                $time =  $messege->time ; 
                echo "
                    <tr>
                        <td>$messege->body</td>
                        <td>$messege->senderName</td>
                        <td>$messege->chatName</td>
                    </tr>
                    ";
                }
            echo "</tbody>
        </table>";
}
function whatsappapi_form (){ ?>
    <form action='options.php' method='post'>
    <?php
    settings_fields( 'pluginPage' );
    do_settings_sections( 'pluginPage' );
    submit_button(__('Save','robo'));
    ?>

</form>
<?php } ; 

function whatsappapi_processes(){ ?>
    <div style="margin-bottom :10px; margin-top :10px">
        <h2><?= __('Processes','robo'); ?></h2>
        <?php 
            if(has_robo_permission('show_msgs')){ ?>
                <a href="<?= current_sub_page_url('&process=show_msgs')."&_wpnonce=".robo_nonce(); ?>" class="button button-primary" ><?= __('Customer Service','robo');?></a>
            <?php }   if(has_robo_permission('send_msg')){ ?> 
        <a href="<?= current_sub_page_url('&process=send_msg'); ?>" class="button button-primary" ><?= __('Send Bulk Messages','robo');?></a>
            <?php } ?> 
        <a href="<?= admin_url('post-new.php?post_type=client'); ?>" target="_blank" class="button button-primary"><?= __('New Client','robo');?></a>
    </div>
<?php };
function render_dynamic_table($headers,$body){ 
    ?>
    
	<!-- Table start -->
    <table class="wp-list-table widefat fixed striped posts tablemanager">
    	<thead>
    		<tr>
                <?php 
                    foreach ($headers as $key => $header) {
                        echo '<th class="disableSort">'.$header.'</th>';
                    }
                ?>
			</tr>
    	</thead>
		<tbody>
            <?php
                foreach ((array)$body as $key => $row) {
                    echo "<tr>";
                        foreach ($row as $item_key => $row_item) {
                            echo "<td scope='col' id='$item_key' class='manage-column column-$item_key'>$row_item</td>";

                            //echo "<td>$row_item</td>"
                        }
                    echo "</tr>";
                }

            ?>
		</tbody>
	</table>

    <!-- External jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<!-- <script type="text/javascript" src="./js/jquery-1.12.3.min.js"></script> -->
    <script type="text/javascript" src="<?= js.'tableManager.js'?>"></script>

	<script type="text/javascript">
		// basic usage
		$('.tablemanager').tablemanager({
			firstSort: [[3,0],[2,0],[1,'asc']],
			disable: ["last"],
			appendFilterby: true,
			dateFormat: [[4,"mm-dd-yyyy"]],
			debug: true,
			vocabulary: {
    voc_filter_by: <?= __('Search By','robo');?>,
    voc_type_here_filter: <?= __('Search','robo');?>,
    voc_show_rows: 'Rows Per Page'
  },
			pagination: true,
			disableFilterBy: [1]
		});
	</script>
	 
<?php }


////////////////////////////////////////////// WHATSAPP SIMULATION //////////////////////////////////////////////
