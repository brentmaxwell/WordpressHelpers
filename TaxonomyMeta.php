<?php

class TaxonomyMetaType extends CustomPostType{
	
	public function __construct(){
		$this->post_type = 'taxonomy-meta';
		$this->options = array(
			'labels'              => array(
				'name'                => esc_html__( 'Taxonomy Meta' ),
				'singular_name'       => esc_html__( 'Taxonomy Meta' ),
				'menu_name'           => esc_html__( 'Taxonomy Meta' ),
				'name_admin_bar'      => esc_html__( 'Taxonomy Meta' ),
				'all_items'           => esc_html__( 'All Taxonomy Meta' ),
				'add_new'             => esc_html__( 'Add New' ),
				'add_new_item'        => esc_html__( 'Add New Taxonomy Meta'),
				'edit_item'           => esc_html__( 'Edit Taxonomy Meta' ),
				'new_item'            => esc_html__( 'New Taxonomy Meta' ),
				'view_item'           => esc_html__( 'View Taxonomy Meta' ),
				'search_items'        => esc_html__( 'Search Taxonomy Meta' ),
				'not_found'           => esc_html__( 'No Taxonomy Meta found' ),
				'not_found_in_trash'  => esc_html__( 'No Taxonomy Meta found in Trash' ),
				'parent_item_colon'   => esc_html__( ':' )
			),
			'description'         => __( 'Taxonomy Meta' ),
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => true,
			'show_in_menu'        => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 55,
			'menu_icon'           => 'dashicons-grid-view',
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
			'taxonomies'           => array(),
			'has_archive'          => false,
			//'permalink_epmask'     => '',
			/*'rewrite'              => array(
				//'slug'                 => $this->post_type,
				//'with_front'           => true,
				//'feeds'                => true,
				//'pages'                => true,
				//'ep_mask'              => 
			),*/
			//'query_var'            => 'data',
			'can_export'           => 'true'
		);
		parent::__construct();
	}
}
$taxonomyMetaType = new TaxonomyMetaType();

function get_term_meta($slug, $taxonomy){
	$query = new WP_Query(
		array(
			'post_type' => 'taxonomy-meta',
			'post_excerpt' => $taxonomy,
			'post_name' => $term
		)
	);
	$meta = get_post_meta($query->post->ID);
	return $meta;
}