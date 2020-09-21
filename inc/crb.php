<?php
// init crb 
include_once(inc.'plugins/carbon-fields/carbon-fields-plugin.php');
use Carbon_Fields\Container;
use Carbon_Fields\Field;

$languages =  array(
    'ar' => __('Arabic','robo'),
    'en' => __('English','robo'),
    'fr' => __('French','robo'),
);
Container::make( 'post_meta', 'ChatBox Generall settings' )
    ->where( 'post_type', '=', 'chatbox' )
    ->add_fields( 
        array(
            Field::make( 'select', 'defult_language', __('Defult Language','robo') )
                ->add_options( $languages ),
            Field::make( 'text', 'session_duration', __( 'Session Duration in seconds','robo') )
                ->set_required( true )
                ->set_default_value( '100' )
                ->set_attribute( 'placeholder', '1800' )
                ->set_attribute( 'min', 1 )
                ->set_attribute( 'step', 1 ),
        ) 
    );

    
Container::make( 'post_meta', __('Build Chat bot','robo') )
    ->where( 'post_type', '=', 'chatbox' )
    ->add_tab( __('Arabic','robo'), single_lang_template('ar'))
    ->add_tab( __('English','robo'), single_lang_template('en'))
    ->add_tab( __('French','robo'), single_lang_template('fr'));

    function single_lang_template($slug){
        $level_1_labels = array(
            'plural_name' => __('Welcome Level','robo'),
            'singular_name' => __('Welcome Level','robo'),
            'slug' => 'level_1',
        );
        $level_2_labels = array(
            'plural_name' => __('level 2','robo'),
            'singular_name' => __('level 2','robo'),
            'slug' => 'level_2',
        );
        $level_3_labels = array(
            'plural_name' => __('level 3','robo'),
            'singular_name' => __('level 3','robo'),
            'slug' => 'level_3',
        );
        $level_4_labels = array(
            'plural_name' => __('level 4','robo'),
            'singular_name' => __('level 4','robo'),
            'slug' => 'level_4',
        );

        $text_area_rows = 2 ;
        $body_area_rows = 5 ;
        $checked_value  = 'checked';
        $default_value  = $checked_value;
        $title_schema   = '<%- ($_index+1) %>- <%- header %>';
        
        return array(
            ///////////////////////////////////////  LEVELE 1  ///////////////////////////////////////
            Field::make( 'complex',$slug, __( 'Set '. $level_1_labels['singular_name'] ) )
                ->set_layout('grid')
                ->set_min(1)
                ->set_max(1)
                ->setup_labels( $level_1_labels )
                ->add_fields( array(
                    Field::make( 'text', 'header', __( ' Header' ,'robo') )
                        ->set_required( true ),
                    Field::make('textarea', 'body', __( 'Body' ,'robo') )
                        ->set_rows($body_area_rows),
                    Field::make( 'image', 'image', __( 'Image' ,'robo') )
                        ->set_value_type( 'url' ),
                    Field::make('textarea', 'error', __( 'Error Message' ,'robo') )
                        ->set_rows($text_area_rows),
                    Field::make('textarea', 'footer', __( 'Footer' ,'robo') )
                        ->set_rows($text_area_rows),
                    Field::make( 'separator', 'navigations_separator', __( 'Navigations' ,'robo') )
                        ->set_help_text( '<h6>Hint</h6><p>You can use any format not  number greater than 0  like  0  , # , * , @ , any letter/s  </p>','robo'),
                    Field::make( 'text', 'one_step_back', __( 'One Step Back' ,'robo') )
                        ->set_attribute( 'placeholder', '0' )
                        ->set_attribute( 'maxLength', 1 )
                        ->set_width( 33 ),
                    Field::make( 'text', 'back_home', __( 'Back Home' ,'robo') )
                        ->set_attribute( 'placeholder', '*' )
                        ->set_attribute( 'maxLength', 1 )
                        ->set_width( 33 ),
                    Field::make( 'separator', 'languages_separator', __( 'Languages '. $default_value ,'robo') ),
                    Field::make( 'checkbox', 'ar_checkbox', __( 'Arabic' ,'robo') )
                        ->set_option_value( $checked_value )
                        ->set_default_value( $default_value )
                        ->set_width( 33 ),
                    Field::make( 'checkbox', 'en_checkbox', __( 'English' ,'robo') )
                        ->set_default_value( $default_value )
                        ->set_option_value( $checked_value )
                        ->set_width( 33 ),
                    Field::make( 'checkbox', 'fr_checkbox', __( 'French' ,'robo') )
                        ->set_option_value( $checked_value )
                        ->set_default_value( $default_value )
                        ->set_width( 33 ),
                    Field::make( 'text', 'ar_slug', __('Arabic Slug','robo') )
                        ->set_width( 33 )
                        ->set_conditional_logic( 
                            array(
                                array(
                                    'field' => 'ar_checkbox',
                                    'value' => true,
                                )
                            ) 
                        ),
                    Field::make( 'text', 'en_slug', __('English Slug','robo') )
                        ->set_width( 33 )
                        ->set_conditional_logic( 
                            array(
                                array(
                                    'field' => 'en_checkbox',
                                    'value' => true,
                                )
                            ) 
                        ),
                    Field::make( 'text', 'fr_slug', __('French Slug','robo') )
                        ->set_width( 33 )
                        ->set_conditional_logic( array(
                                array(
                                    'field' => 'fr_checkbox',
                                    'value' => true,
                                )
                            ) 
                        ),
                    ///////////////////////////////////////  LEVELE 2  ///////////////////////////////////////
                    Field::make( 'complex', 'childs' , __( 'Set '. $level_2_labels['singular_name'] ,'robo') )
                        ->set_layout('tabbed-vertical')
                        //->setup_labels( $level_2_labels )
                        ->add_fields( array(
                            Field::make( 'text', 'header', __( 'Header' ,'robo') )
                                ->set_required( true ),
                            Field::make( 'radio', 'type', __('Type','robo') )
                                ->add_options( array( 'menu' => __('Menu','robo'), 'information' => __('Information','robo'), ) ),
                            Field::make( 'textarea', 'body', __( 'Body' ,'robo') )
                                ->set_rows($body_area_rows),
                            Field::make( 'image', 'image', __( 'Image' ,'robo') )
                                ->set_conditional_logic( array(
                                    'relation' => 'AND',
                                    array(
                                        'field' => 'type',
                                        'value' => 'information',
                                        'compare' => '=',
                                    )
                                ) )    
                                ->set_value_type( 'url' ),
                            Field::make( 'textarea', 'footer', __( 'Footer' ,'robo') )
                                ->set_rows($text_area_rows),
                            ///////////////////////////////////////  LEVELE 3  ///////////////////////////////////////
                            Field::make( 'complex', 'childs' , __( 'Set '. $level_3_labels['singular_name'] ,'robo') )
                            ->set_conditional_logic( array(
                                'relation' => 'AND',
                                array(
                                    'field' => 'type',
                                    'value' => 'information',
                                    'compare' => '!=',
                                )
                            ) )
                            ->set_layout('tabbed-vertical')
                            ->add_fields( array(
                                Field::make( 'text', 'header', __( 'Header' ,'robo') )
                                    ->set_required( true ),
                                Field::make( 'radio', 'type', 'Type' )
                                    ->add_options( array( 'menu' => __('Menu','robo'), 'information' => __('Information','robo'), ) ),
                                Field::make( 'textarea', 'body', __( 'Body' ,'robo') )
                                    ->set_rows($body_area_rows),
                                Field::make( 'image', 'image', __( 'Image' ,'robo') )
                                    ->set_conditional_logic( array(
                                        'relation' => 'AND',
                                        array(
                                            'field' => 'type',
                                            'value' => 'information',
                                            'compare' => '=',
                                        )
                                    ) )    
                                    ->set_value_type( 'url' ),
                                Field::make( 'textarea', 'footer', __( 'Footer' ,'robo') )
                                    ->set_rows($text_area_rows),   
                                ///////////////////////////////////////  LEVELE 4  ///////////////////////////////////////
                                Field::make( 'complex', 'childs' , __( 'Set '. $level_4_labels['singular_name'] ,'robo') )
                                    ->set_conditional_logic(
                                        array(
                                            'relation' => 'AND',
                                            array(
                                                'field' => 'type',
                                                'value' => 'information',
                                                'compare' => '!=',
                                            )
                                        )
                                    )
                                    ->set_layout('tabbed-vertical')
                                    ->add_fields( array(
                                        Field::make( 'text', 'header', __( 'Header' ,'robo') )
                                            ->set_required( true ),
                                        Field::make( 'textarea', 'body', __( 'Body' ,'robo') )
                                            ->set_rows($body_area_rows),
                                        Field::make( 'image', 'image', __( 'Image' ,'robo') )
                                            ->set_value_type( 'url' ),                    
                                        Field::make( 'textarea', 'footer', __( 'Footer' ,'robo') )
                                            ->set_rows($text_area_rows), 
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