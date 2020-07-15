<?php
// init crb 
include_once(inc.'plugins/carbon-fields/carbon-fields-plugin.php');
use Carbon_Fields\Container;
use Carbon_Fields\Field;

$languages =  array(
    'ar' => 'Arabic',
    'en' => 'English',
    'fr' => 'French',
);
$text_area_rows =  2 ; 
Container::make( 'post_meta', 'ChatBox Generall settings' )
    ->where( 'post_type', '=', 'chatbox' )
    ->add_fields( array(
        Field::make( 'select', 'defult_language', 'Defult Language' )
        ->add_options( $languages ),
        Field::make( 'text', 'session_duration', __( 'Session Duration in seconds' ) )
        ->set_attribute( 'placeholder', '1800' )
        ->set_attribute( 'min', 1 )
        ->set_attribute( 'step', 1 ),
    ) );

    
$level_1_labels = array(
    'plural_name' => 'Welcome Level',
    'singular_name' => 'Welcome Level',
    'slug' => 'level_1',
);
$level_2_labels = array(
    'plural_name' => 'level 2',
    'singular_name' => 'level 2',
    'slug' => 'level_2',
);
$level_3_labels = array(
    'plural_name' => 'level 3',
    'singular_name' => 'level 3',
    'slug' => 'level_3',
);
$level_4_labels = array(
    'plural_name' => 'level 4',
    'singular_name' => 'level 4',
    'slug' => 'level_4',
);
Container::make( 'post_meta', 'Build Chat bot' )
    ->where( 'post_type', '=', 'chatbox' )
    ->add_tab( __('Arabic'), single_lang_template('ar') )
    ->add_tab( __('English'), single_lang_template('en') )
    ->add_tab( __('French'), single_lang_template('fr') )
    ;

    function single_lang_template($slug){
        global  $level_1_labels ;
        global  $level_2_labels ;
        global  $level_3_labels ;
        global  $level_4_labels ;
        $checked_value = 'checked';
        $title_schema =  '<%- ($_index+1) %>- <%- header %>';
        
        return array(
            ///////////////////////////////////////  LEVELE 1  ///////////////////////////////////////
            Field::make( 'complex',$slug, __( 'Set '. $level_1_labels['singular_name'] ) )
                ->set_layout('grid')
                ->set_min(1)
                ->set_max(1)
                ->setup_labels( $level_1_labels )
                ->add_fields( array(
                    Field::make( 'text', 'header', __( ' Header' ) )
                    ->set_required( true ),

                    Field::make( 'textarea', 'body', __( ' Body' ) )
                        ->set_rows($text_area_rows),
                    Field::make( 'text', 'error', __( 'Error Message' ) ),
                    Field::make( 'text', 'footer', __( ' Footer' ) ),



                    Field::make( 'separator', 'navigations_separator', __( 'Navigations' ) ),
                    Field::make( 'text', 'one_step_back', __( 'One Step Back' ) )
                    ->set_attribute( 'placeholder', '0' )
                    ->set_attribute( 'maxLength', 1 )
                    ->set_width( 33 )
                    ,
                    Field::make( 'text', 'back_home', __( 'Back Home' ) )
                    ->set_attribute( 'placeholder', '*' )
                    ->set_attribute( 'maxLength', 1 )
                    ->set_width( 33 )
                    ,
                

                    Field::make( 'separator', 'languages_separator', __( 'Languages '. $default_value ) ),
                    Field::make( 'checkbox', 'ar_checkbox', __( 'Arabic' ) )
                    ->set_option_value( $checked_value )
                    ->set_default_value( $default_value )
                    ->set_width( 33 )
                    ,
                    Field::make( 'checkbox', 'en_checkbox', __( 'English' ) )
                    ->set_default_value( $default_value )
                    ->set_option_value( $checked_value )
                    ->set_width( 33 )
                    ,
                    Field::make( 'checkbox', 'fr_checkbox', __( 'French' ) )
                    ->set_option_value( $checked_value )
                    ->set_default_value( $default_value )
                    ->set_width( 33 )
                    ,
                    Field::make( 'text', 'ar_slug', 'Arabic Slug' )
                    ->set_width( 33 )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'ar_checkbox',
                            'value' => true,
                        )
                    ) )
                    ,
                    Field::make( 'text', 'en_slug', 'English Slug' )
                    ->set_width( 33 )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'en_checkbox',
                            'value' => true,
                        )
                    ) )
                    ,
                    Field::make( 'text', 'fr_slug', 'French Slug' )
                    ->set_width( 33 )
                    ->set_conditional_logic( array(
                        array(
                            'field' => 'fr_checkbox',
                            'value' => true,
                        )
                    ) )
                    ,

                    ///////////////////////////////////////  LEVELE 2  ///////////////////////////////////////
                    Field::make( 'complex', 'childs' , __( 'Set '. $level_2_labels['singular_name'] ) )
                        ->set_layout('tabbed-vertical')
                        //->setup_labels( $level_2_labels )
                        ->add_fields( array(
                            Field::make( 'text', 'header', __( ' Header' ) )
                            ->set_required( true ),
                            Field::make( 'textarea', 'body', __( ' Body' ) )
                            ->set_rows($text_area_rows)
                            ,
                            Field::make( 'text', 'footer', __( ' Footer' ) ),
                            ///////////////////////////////////////  LEVELE 3  ///////////////////////////////////////
                            Field::make( 'complex', 'childs' , __( 'Set '. $level_3_labels['singular_name'] ) )
                            ->set_layout('tabbed-vertical')
                            //->setup_labels( $level_3_labels )
                            ->add_fields( array(
                                Field::make( 'text', 'header', __( ' Header' ) )
                                ->set_required( true ),
                                Field::make( 'textarea', 'body', __( ' Body' ) )
                                ->set_rows($text_area_rows)
                                ,
                                Field::make( 'text', 'footer', __( ' Footer' ) ),   
                                ///////////////////////////////////////  LEVELE 4  ///////////////////////////////////////
                                Field::make( 'complex', 'childs' , __( 'Set '. $level_4_labels['singular_name'] ) )
                                    ->set_layout('tabbed-vertical')
                                    //->setup_labels( $level_4_labels )
                                    ->add_fields( array(
                                        Field::make( 'text', 'header', __( ' Header' ) )
                                        ->set_required( true ),
                                        Field::make( 'textarea', 'body', __( ' Body' ) ),
                                        Field::make( 'text', 'footer', __( ' Footer' ) ),   
                                    ) )
                                    ->set_header_template($title_schema),

                            ) )
                            ->set_header_template($title_schema),
                        ) )
                        ->set_header_template($title_schema),
                ) )
                //->set_header_template(' 66 <%- ($_index+1) %> '),
                ->set_header_template($level_1_labels['singular_name'] ),
        );
    };