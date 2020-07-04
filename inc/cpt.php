<?php
/////////////////////////////////////////////////////////////  Posts /////////////////////////////////////////////////////////////
function cpt_clients() {

	/**
	 * Post Type: Clients.
	 */

	$labels = [
		"name" => __( "Clients", "twentytwenty" ),
		"singular_name" => __( "Client", "twentytwenty" ),
		"menu_name" => __( "Clients", "twentytwenty" ),
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

	register_post_type( "client", $args );
};
function cpt_templates() {

	/**
	 * Post Type: Message Template.
	 */

	$labels = [
		"name" => __( "Templates", "twentytwenty" ),
		"singular_name" => __( "Template", "twentytwenty" ),
		"menu_name" => __( "Templates", "twentytwenty" ),
		"all_items" => __( "All Templates", "twentytwenty" ),
		"add_new" => __( "Add new", "twentytwenty" ),
		"add_new_item" => __( "Add new Template", "twentytwenty" ),
		"edit_item" => __( "Edit Template", "twentytwenty" ),
		"new_item" => __( "New Template", "twentytwenty" ),
		"view_item" => __( "View Template", "twentytwenty" ),
		"view_items" => __( "View Templates", "twentytwenty" ),
		"search_items" => __( "Search Templates", "twentytwenty" ),
		"not_found" => __( "No Templates found", "twentytwenty" ),
		"not_found_in_trash" => __( "No Templates found in trash", "twentytwenty" ),
		"parent" => __( "Parent Template:", "twentytwenty" ),
		"featured_image" => __( "Featured image for this Template", "twentytwenty" ),
		"set_featured_image" => __( "Set featured image for this Template", "twentytwenty" ),
		"remove_featured_image" => __( "Remove featured image for this Template", "twentytwenty" ),
		"use_featured_image" => __( "Use as featured image for this Template", "twentytwenty" ),
		"archives" => __( "Template archives", "twentytwenty" ),
		"insert_into_item" => __( "Insert into Template", "twentytwenty" ),
		"uploaded_to_this_item" => __( "Upload to this Template", "twentytwenty" ),
		"filter_items_list" => __( "Filter Templates list", "twentytwenty" ),
		"items_list_navigation" => __( "Templates list navigation", "twentytwenty" ),
		"items_list" => __( "Templates list", "twentytwenty" ),
		"attributes" => __( "Templates attributes", "twentytwenty" ),
		"name_admin_bar" => __( "Template", "twentytwenty" ),
		"item_published" => __( "Template published", "twentytwenty" ),
		"item_published_privately" => __( "Template published privately.", "twentytwenty" ),
		"item_reverted_to_draft" => __( "Template reverted to draft.", "twentytwenty" ),
		"item_scheduled" => __( "Template scheduled", "twentytwenty" ),
		"item_updated" => __( "Template updated.", "twentytwenty" ),
		"parent_item_colon" => __( "Parent Template:", "twentytwenty" ),
	];

	$args = [
		"label" => __( "Templates", "twentytwenty" ),
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
		"rewrite" => [ "slug" => "Templates", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor"],
	];

	register_post_type( "template", $args );
}
function cptui_register_my_cpts_chatbox() {

	/**
	 * Post Type: Chat Boxes.
	 */

	$labels = [
		"name" => __( "Chat Boxes", "twentytwenty" ),
		"singular_name" => __( "Chat Box", "twentytwenty" ),
		"menu_name" => __( "Chat Boxes", "twentytwenty" ),
		"all_items" => __( "All Chat Boxes", "twentytwenty" ),
		"add_new" => __( "Add new", "twentytwenty" ),
		"add_new_item" => __( "Add new Chat Box", "twentytwenty" ),
		"edit_item" => __( "Edit Chat Box", "twentytwenty" ),
		"new_item" => __( "New Chat Box", "twentytwenty" ),
		"view_item" => __( "View Chat Box", "twentytwenty" ),
		"view_items" => __( "View Chat Boxes", "twentytwenty" ),
		"search_items" => __( "Search Chat Boxes", "twentytwenty" ),
		"not_found" => __( "No Chat Boxes found", "twentytwenty" ),
		"not_found_in_trash" => __( "No Chat Boxes found in trash", "twentytwenty" ),
		"parent" => __( "Parent Chat Box:", "twentytwenty" ),
		"featured_image" => __( "Featured image for this Chat Box", "twentytwenty" ),
		"set_featured_image" => __( "Set featured image for this Chat Box", "twentytwenty" ),
		"remove_featured_image" => __( "Remove featured image for this Chat Box", "twentytwenty" ),
		"use_featured_image" => __( "Use as featured image for this Chat Box", "twentytwenty" ),
		"archives" => __( "Chat Box archives", "twentytwenty" ),
		"insert_into_item" => __( "Insert into Chat Box", "twentytwenty" ),
		"uploaded_to_this_item" => __( "Upload to this Chat Box", "twentytwenty" ),
		"filter_items_list" => __( "Filter Chat Boxes list", "twentytwenty" ),
		"items_list_navigation" => __( "Chat Boxes list navigation", "twentytwenty" ),
		"items_list" => __( "Chat Boxes list", "twentytwenty" ),
		"attributes" => __( "Chat Boxes attributes", "twentytwenty" ),
		"name_admin_bar" => __( "Chat Box", "twentytwenty" ),
		"item_published" => __( "Chat Box published", "twentytwenty" ),
		"item_published_privately" => __( "Chat Box published privately.", "twentytwenty" ),
		"item_reverted_to_draft" => __( "Chat Box reverted to draft.", "twentytwenty" ),
		"item_scheduled" => __( "Chat Box scheduled", "twentytwenty" ),
		"item_updated" => __( "Chat Box updated.", "twentytwenty" ),
		"parent_item_colon" => __( "Parent Chat Box:", "twentytwenty" ),
	];

	$args = [
		"label" => __( "Chat Boxes", "twentytwenty" ),
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
		"rewrite" => [ "slug" => "chatbox", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
	];

	register_post_type( "chatbox", $args );
}

add_action( 'init', 'cptui_register_my_cpts_chatbox' );
/////////////////////////////////////////////////////////////  Taxonomies /////////////////////////////////////////////////////////////
function cptui_register_my_taxes_list() {

	/**
	 * Taxonomy: Clients Lists.
	 */

	$labels = [
		"name" => __( "Clients Lists", "twentytwenty" ),
		"singular_name" => __( "Client List", "twentytwenty" ),
		"menu_name" => __( "Lists", "twentytwenty" ),
		"all_items" => __( "All Lists", "twentytwenty" ),
		"edit_item" => __( "Edit List", "twentytwenty" ),
		"view_item" => __( "View List", "twentytwenty" ),
		"update_item" => __( "Update List name", "twentytwenty" ),
		"add_new_item" => __( "Add new List", "twentytwenty" ),
		"new_item_name" => __( "New List name", "twentytwenty" ),
		"parent_item" => __( "Parent List", "twentytwenty" ),
		"parent_item_colon" => __( "Parent List:", "twentytwenty" ),
		"search_items" => __( "Search Lists", "twentytwenty" ),
		"popular_items" => __( "Popular Lists", "twentytwenty" ),
		"separate_items_with_commas" => __( "Separate Lists with commas", "twentytwenty" ),
		"add_or_remove_items" => __( "Add or remove Lists", "twentytwenty" ),
		"choose_from_most_used" => __( "Choose from the most used Lists", "twentytwenty" ),
		"not_found" => __( "No Lists found", "twentytwenty" ),
		"no_terms" => __( "No Lists", "twentytwenty" ),
		"items_list_navigation" => __( "Lists list navigation", "twentytwenty" ),
		"items_list" => __( "Lists list", "twentytwenty" ),
	];

	$args = [
		"label" => __( "Clients Lists", "twentytwenty" ),
		"labels" => $labels,
		"public" => true,
		"publicly_queryable" => true,
		"hierarchical" => true,
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => [ 'slug' => 'list', 'with_front' => true, ],
		"show_admin_column" => false,
		"show_in_rest" => true,
		"rest_base" => "list",
		"rest_controller_class" => "WP_REST_Terms_Controller",
		"show_in_quick_edit" => false,
		];
	register_taxonomy( "list", [ "client" ], $args );
}
function cptui_register_my_taxes_template_category() {

	/**
	 * Taxonomy: Template Categories.
	 */

	$labels = [
		"name" => __( "Template Categories", "twentytwenty" ),
		"singular_name" => __( "Template Categories", "twentytwenty" ),
		"menu_name" => __( "Template Categories", "twentytwenty" ),
		"all_items" => __( "All Template Categories", "twentytwenty" ),
		"edit_item" => __( "Edit Template Categories", "twentytwenty" ),
		"view_item" => __( "View Template Categories", "twentytwenty" ),
		"update_item" => __( "Update Template Categories name", "twentytwenty" ),
		"add_new_item" => __( "Add new Template Categories", "twentytwenty" ),
		"new_item_name" => __( "New Template Categories name", "twentytwenty" ),
		"parent_item" => __( "Parent Template Categories", "twentytwenty" ),
		"parent_item_colon" => __( "Parent Template Categories:", "twentytwenty" ),
		"search_items" => __( "Search Template Categories", "twentytwenty" ),
		"popular_items" => __( "Popular Template Categories", "twentytwenty" ),
		"separate_items_with_commas" => __( "Separate Template Categories with commas", "twentytwenty" ),
		"add_or_remove_items" => __( "Add or remove Template Categories", "twentytwenty" ),
		"choose_from_most_used" => __( "Choose from the most used Template Categories", "twentytwenty" ),
		"not_found" => __( "No Template Categories found", "twentytwenty" ),
		"no_terms" => __( "No Template Categories", "twentytwenty" ),
		"items_list_navigation" => __( "Template Categories list navigation", "twentytwenty" ),
		"items_list" => __( "Template Categories list", "twentytwenty" ),
	];
}