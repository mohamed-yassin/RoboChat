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
    echo '<br><h2> عرض الرسائل  </h2><table class="wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <th scope="col" id="author" class="manage-column column-author">نص الرساله</th>
                    <th scope="col" id="categories" class="manage-column column-categories">المرسل</th>
                    <th scope="col" id="tags" class="manage-column column-tags">اسم المحادثه</th>
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
    submit_button('احفظ');
    ?>

</form>
<?php } ; 

function whatsappapi_processes(){ ?>
    <div style="margin-bottom :10px; margin-top :10px">
        <h2>عمليات</h2>
        <a href="<?= current_sub_page_url('&process=show_msgs'); ?>" class="button button-primary" > نافذه خدمه العملاء  </a>
        <a href="<?= current_sub_page_url('&process=send_msg'); ?>" class="button button-primary" > ارسال رسائل جماعية </a>
        <a href="<?= admin_url('post-new.php?post_type=client'); ?>" target="_blank" class="button button-primary">عميل جديد</a>
    </div>
<?php };
function whatsapp_compose_messege($api,$token,$sub){
    ?>
    <h2>انشاء رساله جديدة</h2>
    <form action="<?= esc_url( admin_url('admin-post.php') ); ?>" method="post">
    
    <input type="hidden" name="action" value="compose_messages">
    <input type="hidden" name="redirect" value="<?= current_sub_page_url('&process=send_messege'); ?>">
    <input type="hidden" name="table" value="<?= related_msgs_table(); ?>">
    <textarea  name="messege" placeholder ="نص الرساله" style="width :  90%" required></textarea>
    <div class ="clients">
        <?php 
        // prepare data
        $clients =get_clients();

        // pre($customer_subscriptions);
        foreach ($clients as $key => $client) {
            $table_body[$key]['ID'] 			=  '<input name="phones[]" value="'.$client->ID.'" type="checkbox" id="check_'.$client->ID.'" > '; 
            $table_body[$key]['post_title'] 	=  $client->post_title;
            $table_body[$key]['phone'] 	=  $client->phone;
        }
        $header = array(
            '#'			    => ' ',
            'post_title' 	=> 'اسم العميل', 
            'phone'			=> 'رقم الهاتف',
        );
        echo "<h2>اختر عملاء الرسالة</h2>";
        render_dynamic_table($header,$table_body);



$lists = get_terms([
    'taxonomy' => 'list',
    'hide_empty' => false,
]);


$table_body = array();
// pre($customer_subscriptions);
foreach ($lists as $key => $list) {
    $table_body[$key]['term_id'] 			=  '<input name="lists[]" value="'.$list->term_id.'" type="checkbox" id="check_'.$list->term_id.'" > '; 
    $table_body[$key]['name'] 	=  $list->name;
    $table_body[$key]['count'] 	=  $list->count;
}
$header = array(
    'term_id'   => ' ',
    'name'      => 'قائمه العملاء', 
    'count'     => 'عدد العملاء بداخلها',
);
echo "<h2>اختر من قوائم العملاء</h2>";

render_table($header,$table_body);

?>    
    <center><input style ="margin : 30px" class="button button-primary"  value="اضف في طابور الرسائل" type="submit" ><a href="<?= admin_url('post-new.php?post_type=client'); ?>" target="_blank" style ="margin : 30px" class="button button-primary">عميل جديد</a></center>
    </form>
    </div>
<?php } ;
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
                foreach ($body as $key => $row) {
                    echo "<tr>";
                        foreach ($row as $item_key => $row_item) {
                            echo "<td scope='col' id='$row_item_key' class='manage-column column-$row_item_key'>$row_item</td>";

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
    voc_filter_by: 'البحث ب',
    voc_type_here_filter: 'ابحث',
    voc_show_rows: 'Rows Per Page'
  },
			pagination: true,
			disableFilterBy: [1]
		});
	</script>
	 
<?php }


////////////////////////////////////////////// WHATSAPP SIMULATION //////////////////////////////////////////////
