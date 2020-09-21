<h2><?= __('Scan the code using the WhatsApp app,then reload the page again','robo');?></h2>
<img id='qr_img' src="<?= $qr ?> " alt="Base64 encoded image"/>

<script>
    setInterval(function() {
        var sub   = document.getElementById('sub').value ; 
        jQuery.ajax({
            type: "post",url: "admin-ajax.php",data: { action: 'check_connection_status_action' ,  sub :  sub  },
            success: function(rsp){ 
                if(rsp  !=  1){
                    location.reload(); 
                }
            }
        });
    }, 1000 *  3 * 1 );
</script>