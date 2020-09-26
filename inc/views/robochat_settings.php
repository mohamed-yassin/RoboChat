<?php
foreach ($user_blogs as $key => $blog) {
    if(is_array($blog)){
        $user_blogs[$key] =  $blog['slug'];
    }
}
if(get_current_blog_id() != 1  &&  is_array($user_blogs)){
    foreach ($user_blogs as $blog) { 
        $name=  'sub_'.$blog.'_name';
        ?>
        <tr><th scope="row"><h6 style="font-weight:bold"><?= "#".$blog.' '. __('Subscription','robo'); ?></h6></th><td></td></tr>
        <tr>
            <th scope="row"><?= __('Name','robo');?></th>
            <td>
                <input type="text" name="roboChat_settings[<?= $name ?>]" value='<?=  isset($options[$name]) ?  $options[$name] :  '' ; ?>'>
            </td>
        </tr>
    <?php 
    
    $name=  'sub_'.$blog.'_chatbox';
    $value =  isset($options[$name]) && $options[$name] >  0 ? $options[$name] :  0 ;
    ?>
    <tr>
        <th scope="row"><?= __('ChatBot');?></th>
        <td>
            <?php
                $title=  __('No Automated Answering','robo') ;
                $val =  0 ; 
                $id=  $val."_".$blog;
                $checked =  $value == $val ? 'checked'  : '';
                echo "<input id='$id'  type='radio' name='roboChat_settings[$name]' $checked value='$val'>";
                echo "<label for='$id'>$title</label>";

                foreach ($chat_boxex as $chat_box) {
                    $title=  $chat_box->post_title ;
                    $val =  $chat_box->ID ; 
                    $id=  $val."_".$blog;
                    $checked =  $value == $val ? 'checked'  : '';
                    echo "<input id='$id' type='radio' name='roboChat_settings[$name]' $checked value='$val'>";
                    echo "<label for='$id'>$title</label>";
                }
            ?>
        </td>
    </tr>

    <?php }
}