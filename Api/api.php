<?php

class Dashboard_PostTypes_Api extends CustomPostType{
	
	public function __construct(){
		$this->post_type = 'api';
		$this->options = array(
			'labels'              => array(
				'name'                => esc_html__( 'API' ),
				'singular_name'       => esc_html__( 'API' ),
				'menu_name'           => esc_html__( 'API' ),
				'name_admin_bar'      => esc_html__( 'API' ),
				'all_items'           => esc_html__( 'All API' ),
				'add_new'             => esc_html__( 'Add New' ),
				'add_new_item'        => esc_html__( 'Add New API'),
				'edit_item'           => esc_html__( 'Edit API' ),
				'new_item'            => esc_html__( 'New API' ),
				'view_item'           => esc_html__( 'View API' ),
				'search_items'        => esc_html__( 'Search API' ),
				'not_found'           => esc_html__( 'No API found' ),
				'not_found_in_trash'  => esc_html__( 'No API found in Trash' ),
				'parent_item_colon'   => esc_html__( ':' )
			),
			'description'         => __( 'Api' ),
			'public'              => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 55,
			'menu_icon'           => 'dashicons-admin-plugins',
			'capability_type'     => 'page',
			//'capabilities'        => array(),
			'map_meta_cap'        => true,
			'hierarchical'        => true,
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
			'query_var'            => 'api',
			'can_export'           => 'true'
		);
		$this->meta_boxes = array(
			array(
				'id' => 'api-data',
				'title' => 'Properties',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'id' => 'content-type',
						'title' => 'Content-type',
						'type' => 'text'
					)
				)
			)
		);
		$this->columns = array(
			'content-type' => 'Content-type'
		);
		parent::__construct();
	}
	
	public function add_columns($columns){
		unset($columns['date']);
		unset($columns['coordinates']);
		unset($columns['address']);
		unset($columns['map']);
		return parent::add_columns($columns);
	}
}
$dashboard_PostTypes_Api = new Dashboard_PostTypes_Api();