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

    <h2>عمليات</h2>

    <a href="<?php echo current_sub_page_url('&process=show_messeges'); ?>" class="button button-primary" > مشاهده الرسائل  </a>
    <a href="<?php echo current_sub_page_url('&process=send_messege'); ?>" class="button button-primary" > ارسال رساله نصيه </a>

<?php };
function whatsapp_compose_messege($api,$token,$sub){
    ?>
    <h2>انشاء رساله جديدة</h2>
    <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
    <textarea  name="messege" placeholder ="نص الرساله" style="width :  90%"></textarea>
    <div class ="clients">
        <?php 
        // prepare data
        $clients =get_clients_list();

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
        render_table($header,$table_body);



$lists = get_terms([
    'taxonomy' => 'list',
    'hide_empty' => false,
]);


$table_body = array();
// pre($customer_subscriptions);
foreach ($lists as $key => $list) {
    $table_body[$key]['term_id'] 			=  '<input name="phones[]" value="'.$list->term_id.'" type="checkbox" id="check_'.$list->term_id.'" > '; 
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
    <input type="hidden" name="action" value="compose_messages">
    <input type="hidden" name="redirect" value="<?php echo current_sub_page_url('&process=send_messege'); ?>">
    <input type="hidden" name="table" value="<?php echo related_msgs_table(); ?>">

    
    <center><input style ="margin-top :  30px" class="button button-primary"  value="اضف في طابور الرسائل" type="submit" ></center>
    </form>
    </div>
<?php } ;



////////////////////////////////////////////// WHATSAPP SIMULATION //////////////////////////////////////////////
