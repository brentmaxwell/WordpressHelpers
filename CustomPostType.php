<?php

class CustomPostType{
	public function __construct(){
		register_activation_hook( __FILE__, array($this,'activate' ));
		add_action('init', array( $this, 'register_post_type'));
		add_action( 'add_meta_boxes_'.$this->post_type, array( $this, 'add_meta_box' ) );
		add_filter('manage_edit-'.$this->post_type.'_columns', array($this,'add_columns'));
		add_action('manage_'.$this->post_type.'_posts_custom_column', array($this,'display_columns'), 10, 2);
	}
	
	public function activate(){
		flush_rewrite_rules();
	}
	
	public function register_post_type(){
		register_post_type(
			$this->post_type,
			$this->options
		);
	}
	
	public function add_meta_box(){
		if(isset($this->meta_boxes)){
			foreach($this->meta_boxes as $metabox)
			{
				add_meta_box(
					$metabox['id'],
					__($metabox['title']),
					array($this,'render_meta_box'),
					$this->post_type,
					$metabox['context'],
					$metabox['priority'],
					$metabox
				);
			}
		}
	}
	
	public function render_meta_box($post,$metabox){
		echo "<table>";
		echo "<tbody>";
		
		// Add an nonce field so we can check for it later.
		wp_nonce_field( $args['id'].'_metabox', $args['id'].'_metabox_nonce' );

		$meta = get_post_meta($post->ID);
		foreach($metabox['args']['fields'] as $field){
			echo $this->generate_metabox_field($field['id'],$meta[$field['id']][0],$field['title']);	
		}
		echo "</tbody>";
		echo "</table>";
	}
	
	public function generate_metabox_field($meta_id,$meta_value,$label){
		$output =  '<tr><td><label for="'.$meta_id.'">'.
		__( $label, 'myplugin_textdomain' ).
		'</label></td><td>'.
		'<input type="text" id="'.$meta_id.'" name="'.$meta_id.'"'.
        ' value="' . esc_attr( $meta_value ) . '" size="25" /></td></tr>';
		return $output;
	}
	
	public function add_columns($columns){
		if(isset($this->columns)){
			$columns = array_merge($columns,$this->columns);
		}
		return $columns;
	}
	
	function display_columns($column_name, $id) {
		if(array_key_exists($column_name,$this->columns)){
			echo get_post_meta($id,$column_name,true);
		}
	}   
}