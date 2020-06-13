<?php 
function whatsapp_messeges($api,$token,$filters= array())
{
    $filter_tas_string =  "";
    if(is_array($filters)){
        foreach ($filters as $key => $filter) {
            $filter_tas_string .=  '';
        }
    }
    $url = $api.'messages?token='.$token.$filter_tas_string;


    $result = file_get_contents($url);
    $result = (array)json_decode($result);
    // pre($result);
    return $result['messages'];
}
function whatsapp_send_messege($msg, $num , $sub)
{
    $sub_connection_data = sub_connection_data($sub);
    if($sub_connection_data['msgs'] >  0 ){
        $phone_reciever = $num;
        $messege        = $msg ;
        $url =  $sub_connection_data['api'].'sendMessage?token='.$sub_connection_data['token'];
        $data = [
            'phone' => $phone_reciever, // Receiver phone
            'body' => $messege, // Message
        ];
        $json = json_encode($data); // Encode data to JSON
        $options = stream_context_create(['http' => 
            [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $json
            ]
        ]);
        $response   = file_get_contents($url, false, $options);
        $response   = json_decode($response, TRUE);

        if($response['sent'] ==  1 ){ // sent successfully 
            $response['status'] = 1;
            $response['balance'] = reduce_msgs_counter($sub);
        }else {
            sub_errors_log($sub,$response);
            $response['status'] =  '3'; // for error from api-chat
        }
    }else {
        $response =  array('status' => "2" , 'balance' => 0 );  // if daily msg balance is ranout
        sub_errors_log($sub,$response);
    }
    $response['msg'] =  restore_new_line($msg) ; 
    echo json_encode($response);
}
function whatsapp_log_out($api,$token,$sub)
{
    $url = $api.'logout?token='.$token;
    $data = [
    ];
    $json = json_encode($data); // Encode data to JSON

    // Make a POST request
    $options = stream_context_create(['http' => [
            'method'  => 'POST',
            'header'  => 'Content-type: application/json',
            'content' => $json
        ]
    ]);
    // Send a request
    $result = array();
    $result = file_get_contents($url, false, $options);
    echo '<a href="'.admin_url('admin.php?page='.$sub.'&process=log_out').'" class="button button-primary" > انهاء الجلسه </a>';
}