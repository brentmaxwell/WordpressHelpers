<?php

class CustomPostType{
	public function __construct(){
		register_activation_hook( __FILE__, array($this,'activate' ));
		add_action('init', array( $this, 'register_post_type'));
		add_action( 'add_meta_boxes_'.$this->post_type, array( $this, 'add_meta_box' ) );
		add_filter('manage_edit-'.$this->post_type.'_columns', array($this,'add_columns'));
		add_action('manage_'.$this->post_type.'_posts_custom_column', array($this,'display_columns'), 10, 2);
		add_action( 'save_post', array( $this, 'save_metabox' ) );
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
		wp_nonce_field( $metabox['args']['id'].'_metabox', $metabox['args']['id'].'_metabox_nonce' );

		$meta = get_post_meta($post->ID);
		foreach($metabox['args']['fields'] as $field){
			echo $this->generate_metabox_field($field['id'],$meta[$field['id']][0],$field['title'],$field['type']);	
		}
		echo "</tbody>";
		echo "</table>";
	}
	
	public function generate_metabox_field($meta_id,$meta_value,$label,$type="text"){
		$output =  '<tr><td><label for="'.$meta_id.'">';
		$output .=  __( $label);
		$output .= '</label></td><td>';
		$output .= '<input type="'.$type.'" id="'.$meta_id.'" name="'.$meta_id.'"';
		switch($type)
		{
			case 'checkbox':
				$output .= ' value="1" '.checked( $meta_value, true, false );
				break;
			default:
				$output .= ' value="'.esc_attr( $meta_value ).'"';
				break;
		}
		$output .= '"/></td></tr>';
        
		return $output;
	}
	
	public function save_metabox($post_id){
		foreach($this->meta_boxes as $metabox){
			if ( ! isset( $_POST[$metabox['id'].'_metabox_nonce'] ) ) {
			return;
			}
		
			if ( ! wp_verify_nonce( $_POST[$metabox['id'].'_metabox_nonce'], $metabox['id'].'_metabox' ) ) {
				return;
			}
		
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			
			if ( 'page' == $_POST['post_type'] ) {
				if ( ! current_user_can( 'edit_page', $post_id ) )
					return $post_id;
			} else {
				if ( ! current_user_can( 'edit_post', $post_id ) )
					return $post_id;
			}
	
			foreach($metabox['fields'] as $field){
				$field_data = sanitize_text_field( $_POST[$field['id']] );
				update_post_meta( $post_id, $field['id'], $field_data );	
			}
		}
		
	}
	
	public function add_columns($columns){
		if(isset($this->columns)){
			$columns = array_merge($columns,$this->columns);
		}
		return $columns;
	}
	
	function display_columns($column_name, $id) {
		if(isset($this->columns)){
			if(array_key_exists($column_name,$this->columns)){
				echo get_post_meta($id,$column_name,true);
			}
		}
	}   
}