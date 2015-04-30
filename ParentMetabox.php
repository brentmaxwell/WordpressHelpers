<?php
	class ParentMetabox{
		public function __construct(){
			add_action( 'add_meta_boxes', array( $this, 'add_metaboxes' ) );
			add_filter( 'manage_post_columns', array($this,'add_columns'));
			add_action( 'manage_post_custom_column', array($this,'display_columns'), 10, 2);
		}
		
		public function add_metaboxes(){
			add_meta_box(
				'post_parent_metabox',
				__('Parent'),
				array($this,'render_parent_meta_box'),
				'post',
				'side',
				'high'
			);
		}
		
		public function render_parent_meta_box($post){
			$parents = get_posts(
		        array(
		            'post_type'   => 'post', 
		            'orderby'     => 'post_date', 
		            'order'       => 'DESC', 
		            'numberposts' => -1 
		        )
		    );
			echo '<script type="text/javascript">'."\n";
			echo 'function setParentID(selected){'."\n";
			echo 'document.getElementById("parent_id").value = selected.value;'."\n";
			echo '};'."\n";
			echo '</script>';
			echo "<table>";
			echo "<tbody>";
			// Add an nonce field so we can check for it later.
			wp_nonce_field( 'post_parent_metabox', 'post_parent_metabox_nonce' );
			echo '<tr><td><label for="parent_id">'.__("Parent ID").'</label></td><td><input type="text" id="parent_id" name="parent_id" value="'.$post->post_parent.'"/></td></tr>';
		 	if ( !empty( $parents ) ) {
	        	echo '<tr><td colspan="2"><select id="parent_id_select" class="widefat" onchange="setParentID(this);">';
				echo '<option value="0">--None--</option>';
		        foreach ( $parents as $parent ) {
					if($parent->post_type != $this->post_type){
	    	        	printf( '<option value="%s"%s>%s</option>', esc_attr( $parent->ID ), selected( $parent->ID, $post->post_parent, false ), esc_html( '(' . get_the_date('Y/m/d',$parent->ID) .') ' . $parent->post_title ) );
					}
	        	}
	        	echo '</select></td></tr>';
	    	} 
			if(!empty($post->post_parent) && $post->post_parent != 0){
				echo '<tr><td colspan="2">';
				echo '<a href="'.get_edit_post_link($post->post_parent).'">';
				echo get_the_title($post->post_parent);
				echo '</a>';
				echo "</td></tr>";
			}
			echo "</tbody>";
			echo "</table>";
		}
		
		public function add_columns($columns){
			$columns['parent'] = 'Parent';
			return $columns;
		}
		
		public function display_columns($column_name,$id){
			switch ($column_name) {
				case 'parent':
					$parent_post = get_post_field('post_parent',$id,'raw'); 
					if($parent_post != null){
						echo '<a href="'.get_edit_post_link($parent_post).'">';
						echo get_post_field('post_title',$parent_post);
						echo '</a>';
					}
					break;
		    	default:
			        break;
	    	}
		}
	}
	
	$parentMetabox = new ParentMetabox();