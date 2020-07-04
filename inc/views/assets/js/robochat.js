jQuery('.connection-button').on('click', function( event ){
    api     =  document.getElementById('acf-field_5eb9da33d3d84').value ;
    token   =  document.getElementById('acf-field_5eb9dabad3d85').value ;
    id      =  this.id ;
    post    =  jQuery(this).attr("data-post"); 

    jQuery.ajax({
        type: "post",url: "admin-ajax.php",data: { action: 'chat_api_main_processes_action' , api : api , token :  token  , process :  id , post :  post  },
        beforeSend: function() {jQuery("#helloworld").fadeOut('fast');}, 
        success: function(rsp){ 
            document.getElementById('chat_api_response').innerHTML = rsp; 
        }
    });
});



