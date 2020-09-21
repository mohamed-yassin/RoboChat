<?php
/////////////////////////////////////////////////////////////  Posts /////////////////////////////////////////////////////////////
function cpt_clients() {

	/**
	 * Post Type: Clients.
	 */

	$labels = [
		"name" => __( "Clients", "robo" ),
		"singular_name" => __( "Client", "robo" ),
		"menu_name" => __( "Clients", "robo" ),
		"all_items" => __( "All Clients", "robo" ),
		"add_new" => __( "Add new", "robo" ),
		"add_new_item" => __( "Add new Client", "robo" ),
		"edit_item" => __( "Edit Client", "robo" ),
		"new_item" => __( "New Client", "robo" ),
		"view_item" => __( "View Client", "robo" ),
		"view_items" => __( "View Clients", "robo" ),
		"search_items" => __( "Search Clients", "robo" ),
		"not_found" => __( "No Clients found", "robo" ),
		"not_found_in_trash" => __( "No Clients found in trash", "robo" ),
		"parent" => __( "Parent Client:", "robo" ),
		"featured_image" => __( "Featured image for this Client", "robo" ),
		"set_featured_image" => __( "Set featured image for this Client", "robo" ),
		"remove_featured_image" => __( "Remove featured image for this Client", "robo" ),
		"use_featured_image" => __( "Use as featured image for this Client", "robo" ),
		"archives" => __( "Client archives", "robo" ),
		"insert_into_item" => __( "Insert into Client", "robo" ),
		"uploaded_to_this_item" => __( "Upload to this Client", "robo" ),
		"filter_items_list" => __( "Filter Clients list", "robo" ),
		"items_list_navigation" => __( "Clients list navigation", "robo" ),
		"items_list" => __( "Clients list", "robo" ),
		"attributes" => __( "Clients attributes", "robo" ),
		"name_admin_bar" => __( "Client", "robo" ),
		"item_published" => __( "Client published", "robo" ),
		"item_published_privately" => __( "Client published privately.", "robo" ),
		"item_reverted_to_draft" => __( "Client reverted to draft.", "robo" ),
		"item_scheduled" => __( "Client scheduled", "robo" ),
		"item_updated" => __( "Client updated.", "robo" ),
		"parent_item_colon" => __( "Parent Client:", "robo" ),
	];

	$args = [
		"label" => __( "Clients", "robo" ),
		"labels" => $labels,
		"description" => "",
		"public" => true ,
		"publicly_queryable" => false,
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
		"name" => __( "Templates", "robo" ),
		"singular_name" => __( "Template", "robo" ),
		"menu_name" => __( "Templates", "robo" ),
		"all_items" => __( "All Templates", "robo" ),
		"add_new" => __( "Add new", "robo" ),
		"add_new_item" => __( "Add new Template", "robo" ),
		"edit_item" => __( "Edit Template", "robo" ),
		"new_item" => __( "New Template", "robo" ),
		"view_item" => __( "View Template", "robo" ),
		"view_items" => __( "View Templates", "robo" ),
		"search_items" => __( "Search Templates", "robo" ),
		"not_found" => __( "No Templates found", "robo" ),
		"not_found_in_trash" => __( "No Templates found in trash", "robo" ),
		"parent" => __( "Parent Template:", "robo" ),
		"featured_image" => __( "Featured image for this Template", "robo" ),
		"set_featured_image" => __( "Set featured image for this Template", "robo" ),
		"remove_featured_image" => __( "Remove featured image for this Template", "robo" ),
		"use_featured_image" => __( "Use as featured image for this Template", "robo" ),
		"archives" => __( "Template archives", "robo" ),
		"insert_into_item" => __( "Insert into Template", "robo" ),
		"uploaded_to_this_item" => __( "Upload to this Template", "robo" ),
		"filter_items_list" => __( "Filter Templates list", "robo" ),
		"items_list_navigation" => __( "Templates list navigation", "robo" ),
		"items_list" => __( "Templates list", "robo" ),
		"attributes" => __( "Templates attributes", "robo" ),
		"name_admin_bar" => __( "Template", "robo" ),
		"item_published" => __( "Template published", "robo" ),
		"item_published_privately" => __( "Template published privately.", "robo" ),
		"item_reverted_to_draft" => __( "Template reverted to draft.", "robo" ),
		"item_scheduled" => __( "Template scheduled", "robo" ),
		"item_updated" => __( "Template updated.", "robo" ),
		"parent_item_colon" => __( "Parent Template:", "robo" ),
	];

	$args = [
		"label" => __( "Templates", "robo" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => false,
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
		//"supports" => [ "title", "editor"],
		"supports" => [ "title"],
	];

	register_post_type( "template", $args );
}
function cpt_chatbox() {
	if(! has_robo_permission('chat_bot')){
		return ;
	}



	/**
	 * Post Type: Chat Boxes.
	 */

	$labels = [
		"name" => __( "Chat Boxes", "robo" ),
		"singular_name" => __( "Chat Box", "robo" ),
		"menu_name" => __( "Chat Boxes", "robo" ),
		"all_items" => __( "All Chat Boxes", "robo" ),
		"add_new" => __( "Add new", "robo" ),
		"add_new_item" => __( "Add new Chat Box", "robo" ),
		"edit_item" => __( "Edit Chat Box", "robo" ),
		"new_item" => __( "New Chat Box", "robo" ),
		"view_item" => __( "View Chat Box", "robo" ),
		"view_items" => __( "View Chat Boxes", "robo" ),
		"search_items" => __( "Search Chat Boxes", "robo" ),
		"not_found" => __( "No Chat Boxes found", "robo" ),
		"not_found_in_trash" => __( "No Chat Boxes found in trash", "robo" ),
		"parent" => __( "Parent Chat Box:", "robo" ),
		"featured_image" => __( "Featured image for this Chat Box", "robo" ),
		"set_featured_image" => __( "Set featured image for this Chat Box", "robo" ),
		"remove_featured_image" => __( "Remove featured image for this Chat Box", "robo" ),
		"use_featured_image" => __( "Use as featured image for this Chat Box", "robo" ),
		"archives" => __( "Chat Box archives", "robo" ),
		"insert_into_item" => __( "Insert into Chat Box", "robo" ),
		"uploaded_to_this_item" => __( "Upload to this Chat Box", "robo" ),
		"filter_items_list" => __( "Filter Chat Boxes list", "robo" ),
		"items_list_navigation" => __( "Chat Boxes list navigation", "robo" ),
		"items_list" => __( "Chat Boxes list", "robo" ),
		"attributes" => __( "Chat Boxes attributes", "robo" ),
		"name_admin_bar" => __( "Chat Box", "robo" ),
		"item_published" => __( "Chat Box published", "robo" ),
		"item_published_privately" => __( "Chat Box published privately.", "robo" ),
		"item_reverted_to_draft" => __( "Chat Box reverted to draft.", "robo" ),
		"item_scheduled" => __( "Chat Box scheduled", "robo" ),
		"item_updated" => __( "Chat Box updated.", "robo" ),
		"parent_item_colon" => __( "Parent Chat Box:", "robo" ),
	];

	$args = [
		"label" => __( "Chat Boxes", "robo" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => false,
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
		"supports" => [ "title"  ],
	];

	register_post_type( "chatbox", $args );
}

/////////////////////////////////////////////////////////////  Taxonomies /////////////////////////////////////////////////////////////
function cptui_register_my_taxes_list() {

	/**
	 * Taxonomy: Clients Lists.
	 */

	$labels = [
		"name" => __( "Clients Lists", "robo" ),
		"singular_name" => __( "Client List", "robo" ),
		"menu_name" => __( "Lists", "robo" ),
		"all_items" => __( "All Lists", "robo" ),
		"edit_item" => __( "Edit List", "robo" ),
		"view_item" => __( "View List", "robo" ),
		"update_item" => __( "Update List name", "robo" ),
		"add_new_item" => __( "Add new List", "robo" ),
		"new_item_name" => __( "New List name", "robo" ),
		"parent_item" => __( "Parent List", "robo" ),
		"parent_item_colon" => __( "Parent List:", "robo" ),
		"search_items" => __( "Search Lists", "robo" ),
		"popular_items" => __( "Popular Lists", "robo" ),
		"separate_items_with_commas" => __( "Separate Lists with commas", "robo" ),
		"add_or_remove_items" => __( "Add or remove Lists", "robo" ),
		"choose_from_most_used" => __( "Choose from the most used Lists", "robo" ),
		"not_found" => __( "No Lists found", "robo" ),
		"no_terms" => __( "No Lists", "robo" ),
		"items_list_navigation" => __( "Lists list navigation", "robo" ),
		"items_list" => __( "Lists list", "robo" ),
	];

	$args = [
		"label" => __( "Clients Lists", "robo" ),
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
		"name" => __( "Template Categories", "robo" ),
		"singular_name" => __( "Template Categories", "robo" ),
		"menu_name" => __( "Template Categories", "robo" ),
		"all_items" => __( "All Template Categories", "robo" ),
		"edit_item" => __( "Edit Template Categories", "robo" ),
		"view_item" => __( "View Template Categories", "robo" ),
		"update_item" => __( "Update Template Categories name", "robo" ),
		"add_new_item" => __( "Add new Template Categories", "robo" ),
		"new_item_name" => __( "New Template Categories name", "robo" ),
		"parent_item" => __( "Parent Template Categories", "robo" ),
		"parent_item_colon" => __( "Parent Template Categories:", "robo" ),
		"search_items" => __( "Search Template Categories", "robo" ),
		"popular_items" => __( "Popular Template Categories", "robo" ),
		"separate_items_with_commas" => __( "Separate Template Categories with commas", "robo" ),
		"add_or_remove_items" => __( "Add or remove Template Categories", "robo" ),
		"choose_from_most_used" => __( "Choose from the most used Template Categories", "robo" ),
		"not_found" => __( "No Template Categories found", "robo" ),
		"no_terms" => __( "No Template Categories", "robo" ),
		"items_list_navigation" => __( "Template Categories list navigation", "robo" ),
		"items_list" => __( "Template Categories list", "robo" ),
	];
}