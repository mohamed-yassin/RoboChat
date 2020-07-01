<?php



//Load template from specific page
add_filter( 'page_template', 'wpa3396_page_template' );
function wpa3396_page_template( $page_template ){

    if ( get_page_template_slug() == 'cron_job.php' ) {
        $page_template = dirname( __FILE__ ) . '/cron_job.php';
    }
    return $page_template;
}

/**
 * Add "Custom" template to page attirbute template section.
 */
add_filter( 'theme_page_templates', 'wpse_288589_add_template_to_select', 10, 4 );
function wpse_288589_add_template_to_select( $post_templates, $wp_theme, $post, $post_type ) {

    // Add custom template named template-custom.php to select dropdown 
    $post_templates['cron_job.php'] = __('Configurator');

    return $post_templates;
}


add_action( 'rest_api_init', function () {
    register_rest_route( 'myplugin/v1', '/author/(?P<id>\d+)', array(
      'methods' => 'GET',
      'callback' => 'my_awesome_func',
    ) );
  } );

  function my_awesome_func( $data ) {
    $posts = get_posts( array(
      'author' => $data['id'],
    ) );
   
    if ( empty( $posts ) ) {
      return null;
    }
   
    return $posts[0]->post_title;
  }