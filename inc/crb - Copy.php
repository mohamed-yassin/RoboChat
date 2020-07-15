<?php
// init crb 
include_once(inc.'plugins/carbon-fields/carbon-fields-plugin.php');

use Carbon_Fields\Container;
use Carbon_Fields\Field;

$main_sections_labels = array(
    'plural_name' => 'Main Sections',
    'singular_name' => 'Section',
);
$sub_sections_labels = array(
    'plural_name' => 'Sub Sections',
    'singular_name' => 'Sub Section',
);        

$langs =  array(
    'ar' => 'Arabic',
    'en' => 'English',
    'fr' => 'French',
);
Container::make( 'post_meta', 'ChatBox Generall settings' )
    ->where( 'post_type', '=', 'chatbox' )
    ->add_fields( array(
        Field::make( 'select', 'crb_content_align', __( 'Defult language' ) )
        ->add_options( array(
            'arabic' => __( 'Arabic' ),
            'english' => __( 'English' ),
            'french' => __( 'French' ),
        ) )
    ) );





    Container::make( 'post_meta', __( 'Build Your Chat bot' ) )
        ->where( 'post_type', '=', 'chatbox' )
        ->add_tab( __( 'Arabic' ),    bot_maker_template('ar') )
        ->add_tab( __( 'English' ), bot_maker_template('en') )
        ->add_tab( __( 'French' ), bot_maker_template('fr') )
    ;


    function bot_maker_template($slug){
        return array(
            Field::make( 'text', $slug.'_crb_email', __( 'Notification Email' ) ),
            Field::make( 'complex', $slug.'_main_sections', __( 'Set Your Main Sections' ) )
            ->set_layout('tabbed-horizontal')
            // ->setup_labels( array( 'plural_name' => 'Main Sections', 'singular_name' => 'Section') )
            ->add_fields( array(
                Field::make( 'text', $slug.'_main_section_header', __( 'Header' ) ),
                ///////////////////////////////////////  LEVELE 2  ///////////////////////////////////////
                Field::make( 'complex', $slug.'_sub_sections', __( 'Set Your Sub Sections' ) )
                    ->set_layout('tabbed-vertical')
                    //->setup_labels( array( 'plural_name' => 'Sub Sections', 'singular_name' => 'Sub Section') )
                    ->add_fields( array(
                        Field::make( 'text', $slug.'_title', __( 'Header' ) ),
                        Field::make( 'textarea', $slug.'_crb_phone_numbers', __( 'Body' ) ),
                        Field::make( 'text', $slug.'_title', __( 'Footer' ) ),
                    ) )
            ) )
            ->set_header_template(' <%- ($_index+1) %> '),
        ); 
    };



    Container::make( 'post_meta', __( 'Build Your Chat bot 2' ) )
    ->where( 'post_type', '=', 'chatbox' )
    ->add_fields( array(
        Field::make( 'complex', 'crb_complex', 'My Complex Field' )
            ->add_fields( array(
                Field::make( 'text', 'my_text_field', 'My Text Field' )
            ) )
            ->set_default_value( array(
                array(
                    'my_text_field' => 'Hello',
                ),
                array(
                    'my_text_field' => 'World!',
                ),
            ) ),
    ) );