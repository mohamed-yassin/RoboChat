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
Container::make( 'post_meta', 'ChatBox Generall settings' )
    ->where( 'post_type', '=', 'chatbox' )
    ->add_fields( array(
        Field::make( 'textarea', 'crb_phone_numbers', __( 'Body' ) ),
        Field::make( 'checkbox', 'crb_show_content', 'Show content' ),
        Field::make( 'rich_text', 'crb_content', 'Content' )
            ->set_conditional_logic( array(
                    array(
                        'field' => 'crb_show_content',
                        'value' => true,
                    )
            ) ),
        Field::make( 'multiselect', 'crb_available_colors', __( 'Available Colors' ) )
        ->add_options( array(
            'red' => 'Red',
            'green' => 'Green',
            'blue' => 'Blue',
        ) ),
        Field::make( 'select', 'crb_content_align', __( 'defult language' ) )
        ->add_options( array(
            'arabic' => __( 'Arabic' ),
            'english' => __( 'English' ),
            'french' => __( 'French' ),
        ) )

    ) );


Container::make( 'post_meta', 'Build Your Chat bot' )
    ->where( 'post_type', '=', 'chatbox' )
    ->add_fields( array(
        Field::make( 'text', 'header', __( 'Header' ) ),
        ///////////////////////////////////////  LEVELE 1  ///////////////////////////////////////
        Field::make( 'complex', 'main_sections', __( 'Set Your Main Sections' ) )
            ->set_layout('tabbed-horizontal')
            ->setup_labels( $main_sections_labels )
            ->add_fields( array(
                Field::make( 'text', 'main_section_header', __( 'Header' ) ),
                ///////////////////////////////////////  LEVELE 2  ///////////////////////////////////////
                Field::make( 'complex', 'sub_sections', __( 'Set Your Sub Sections' ) )
                    ->set_layout('tabbed-vertical')
                    ->setup_labels( $sub_sections_labels )
                    ->add_fields( array(
                        Field::make( 'text', 'title', __( 'Header' ) ),
                        Field::make( 'textarea', 'crb_phone_numbers', __( 'Body' ) ),
                        Field::make( 'text', 'title', __( 'Footer' ) ),
                    ) )
            ) )
            ->set_header_template(' <%- ($_index+1) %> '),
        Field::make( 'text', 'footer', __( 'Footer' ) ),
    ) );