<?php
function cpt_clients() {

	/**
	 * Post Type: Clients.
	 */

	$labels = [
		"name" => __( "Clients", "twentytwenty" ),
		"singular_name" => __( "Client", "twentytwenty" ),
		"menu_name" => __( "My Clients", "twentytwenty" ),
		"all_items" => __( "All Clients", "twentytwenty" ),
		"add_new" => __( "Add new", "twentytwenty" ),
		"add_new_item" => __( "Add new Client", "twentytwenty" ),
		"edit_item" => __( "Edit Client", "twentytwenty" ),
		"new_item" => __( "New Client", "twentytwenty" ),
		"view_item" => __( "View Client", "twentytwenty" ),
		"view_items" => __( "View Clients", "twentytwenty" ),
		"search_items" => __( "Search Clients", "twentytwenty" ),
		"not_found" => __( "No Clients found", "twentytwenty" ),
		"not_found_in_trash" => __( "No Clients found in trash", "twentytwenty" ),
		"parent" => __( "Parent Client:", "twentytwenty" ),
		"featured_image" => __( "Featured image for this Client", "twentytwenty" ),
		"set_featured_image" => __( "Set featured image for this Client", "twentytwenty" ),
		"remove_featured_image" => __( "Remove featured image for this Client", "twentytwenty" ),
		"use_featured_image" => __( "Use as featured image for this Client", "twentytwenty" ),
		"archives" => __( "Client archives", "twentytwenty" ),
		"insert_into_item" => __( "Insert into Client", "twentytwenty" ),
		"uploaded_to_this_item" => __( "Upload to this Client", "twentytwenty" ),
		"filter_items_list" => __( "Filter Clients list", "twentytwenty" ),
		"items_list_navigation" => __( "Clients list navigation", "twentytwenty" ),
		"items_list" => __( "Clients list", "twentytwenty" ),
		"attributes" => __( "Clients attributes", "twentytwenty" ),
		"name_admin_bar" => __( "Client", "twentytwenty" ),
		"item_published" => __( "Client published", "twentytwenty" ),
		"item_published_privately" => __( "Client published privately.", "twentytwenty" ),
		"item_reverted_to_draft" => __( "Client reverted to draft.", "twentytwenty" ),
		"item_scheduled" => __( "Client scheduled", "twentytwenty" ),
		"item_updated" => __( "Client updated.", "twentytwenty" ),
		"parent_item_colon" => __( "Parent Client:", "twentytwenty" ),
	];

	$args = [
		"label" => __( "Clients", "twentytwenty" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "clients", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "thumbnail"],
	];

	register_post_type( "clients", $args );
}