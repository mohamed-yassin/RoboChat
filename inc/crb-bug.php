<?php
// init crb 
include_once(inc.'plugins/carbon-fields/carbon-fields-plugin.php');

use Carbon_Fields\Container;
use Carbon_Fields\Field;



$employees_labels = array(
    'plural_name' => 'Messages As Plural',
    'singular_name' => 'Message As Singular',
);

Container::make( 'post_meta', 'Build Your Chat bot' )
    ->where( 'post_type', '=', 'chatbox' )
    ->add_fields( array(
        Field::make( 'complex', 'crb_slider', __( 'tabbed-horizontal grid' ) )
        ->set_layout('tabbed-horizontal')
        ->setup_labels( $employees_labels )
        ->set_header_template( '
            <% if (title) { %>
                Passenger: <%- title %> <%- years ? "(" + years + ")" : "" %>
            <% } %>
        ' )
        ->add_fields( array(
            Field::make( 'text', 'title', __( 'Header' ) ),



            Field::make( 'complex', 'crb_slider', __( 'tabbed-vertical grid' ) )
            ->set_layout('tabbed-vertical')

            ->add_fields( array(
                Field::make( 'text', 'title', __( 'Header' ) ),
                Field::make( 'textarea', 'crb_phone_numbers', __( 'Body' ) ),
                Field::make( 'text', 'title', __( 'Footer' ) ),


                Field::make( 'complex', 'crb_slider', __( 'Child Message' ) )
                ->set_layout('grid')

                ->add_fields( array(
                    Field::make( 'text', 'title', __( 'Header' ) ),
                    Field::make( 'textarea', 'crb_phone_numbers', __( 'Body' ) ),
                    Field::make( 'text', 'title', __( 'Footer' ) ),
                    ) )
    
    
                ) )
            ) )

            
));