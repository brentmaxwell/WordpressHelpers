<?php

class Dashboard_PostTypes_ApiKey extends CustomPostType{
	
	public function __construct(){
		$this->post_type = 'api-key';
		$this->options = array(
			'labels'              => array(
				'name'                => esc_html__( 'API Keys' ),
				'singular_name'       => esc_html__( 'API Key' ),
				'menu_name'           => esc_html__( 'API Keys' ),
				'name_admin_bar'      => esc_html__( 'API Keys' ),
				'all_items'           => esc_html__( 'All API Keys' ),
				'add_new'             => esc_html__( 'Add New' ),
				'add_new_item'        => esc_html__( 'Add New API Key'),
				'edit_item'           => esc_html__( 'Edit API Key' ),
				'new_item'            => esc_html__( 'New API Key' ),
				'view_item'           => esc_html__( 'View API Key' ),
				'search_items'        => esc_html__( 'Search API Keys' ),
				'not_found'           => esc_html__( 'No API Keys found' ),
				'not_found_in_trash'  => esc_html__( 'No API Keys found in Trash' ),
				'parent_item_colon'   => esc_html__( ':' )
			),
			'description'         => __( 'api-key' ),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 55,
			'menu_icon'           => 'dashicons-admin-plugins',
			'capability_type'     => 'post',
			//'capabilities'        => array(),
			'map_meta_cap'        => true,
			'hierarchical'        => false,
			'supports'            => array(
				'title',
				'editor',
				'author',
				'thumbnail',
				'excerpt',
				'trackbacks',
				'custom-fields',
				'comments',
				//'revisions',
				'page-attributes',
				//'post-formats'
			),
			//'register_meta_box_cb' => array($this,''),
			'has_archive'          => true,
			//'permalink_epmask'     => '',
			'rewrite'              => array(
				'slug'                 => $this->post_type,
				//'with_front'           => true,
				//'feeds'                => true,
				//'pages'                => true,
				//'ep_mask'              => 
			),
			'query_var'            => 'api-key',
			'can_export'           => 'true'
		);
		parent::__construct();
	}
}
$dashboard_PostTypes_ApiKey = new Dashboard_PostTypes_ApiKey();