<?php

class GeoHelper_Metabox{
	public function __construct(){
		add_action( 'add_meta_boxes' , array( $this , 'add_metabox' ) );
		add_action( 'save_post' , array( $this , 'save_metabox' ) );
		add_action( 'save_post' , array( $this , 'geocoding' ) );
	}
	
	public function add_metabox(){
		add_meta_box(
			'geo',
			__('Geo'),
			array($this,'render_metabox'),
			null,
			'side'
		);
	}
	
	public function render_metabox( $post, $metabox ){
		echo "<table>";
		echo "<tbody>";
		
		wp_nonce_field( 'geo_metabox', 'geo_metabox_nonce' );

		$meta = get_post_meta($post->ID);
		echo "<tr>";
		echo $this->generate_metabox_field('geo_public',$meta['geo_public'][0],'Public','checkbox');
		echo '</tr>';
		echo "<tr>";
		echo $this->generate_metabox_field('geo_latitude',$meta['geo_latitude'][0],'Latitude');
		echo '</tr>';
		echo "<tr>";
		echo $this->generate_metabox_field('geo_longitude',$meta['geo_longitude'][0],'Longitude');
		echo '</tr>';
		echo '<tr>';
		echo '<td colspan=2">';
		echo $this->generate_metabox_field('geo_address',$meta['geo_address'][0],'Address<br/>','textbox',false);
		echo '</td>';
		echo "</tbody>";
		echo "</table>";
		if(array_key_exists('geo_latitude',$meta) && array_key_exists('geo_longitude',$meta)){
			if( !empty($meta['geo_latitude'][0]) || !empty($meta['geo_longitude'][0]) ){
				$shortcode = '[staticmap height="240" width="240"';
				$shortcode .= ' markers="color:blue|'.$meta['geo_latitude'][0].','.$meta['geo_longitude'][0].'"';
				if(array_key_exists('geo_polyline',$meta)){
					$shortcode .= ' polyline="color:0xFF0000BF|weight:2|enc:'.urlencode($meta['geo_polyline'][0]).'"';
				}
				$shortcode .= ']';
				echo do_shortcode($shortcode);
			}
		}
	}
	
	public function generate_metabox_field($meta_id,$meta_value,$label,$type="text",$columns = true){
		$output = '';
		if($columns){
			$output .= '<td>';
		}
		$output .=  '<label for="'.$meta_id.'">'.__( $label).'</label>';
		if($columns){
			$output .= '</td><td>';
		};
		
		switch($type)
		{
			case 'textbox':
				$output .= '<textarea class="widefat" id="'.$meta_id.'" name="'.$meta_id.'">'.esc_attr($meta_value).'</textarea>';
				break;
			case 'checkbox':
				$output .= '<input class="widefat" type="'.$type.'" id="'.$meta_id.'" name="'.$meta_id.'" value="1" '.checked( $meta_value, true, false ) .'"/>';
				break;
			default:
				$output .= '<input class="widefat" type="'.$type.'" id="'.$meta_id.'" name="'.$meta_id.'" value="'.esc_attr( $meta_value ).'"/>';
				break;
		}
		if($columns){
			$output .= '</td>';
		};
        
		return $output;
	}
	
	public function save_metabox($post_id){
		if ( ! isset( $_POST['geo_metabox_nonce'] ) ) {
			return;
		}
	
		if ( ! wp_verify_nonce( $_POST['geo_metabox_nonce'], 'geo_metabox' ) ) {
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
		delete_post_meta($post_id,'geo_latitude');
		delete_post_meta($post_id,'geo_longitude');
		delete_post_meta($post_id,'geo_public');
		
		if(!empty($_POST['geo_latitude']) && !empty($_POST['geo_longitude'])){
			$geo_latitude = sanitize_text_field( $_POST['geo_latitude'] );
			$geo_longitude = sanitize_text_field( $_POST['geo_longitude'] );
			$geo_public = $_POST['geo_public'];
			
			update_post_meta( $post_id, 'geo_latitude', $geo_latitude );
			update_post_meta( $post_id, 'geo_longitude', $geo_longitude );
			update_post_meta( $post_id, 'geo_public', $geo_public );
			
		}
	}
	
	function geocoding($post_id){
		$process = false;
		$post = get_post($post_id);
		$meta = get_post_meta($post_id);
		$url = "https://maps.googleapis.com/maps/api/geocode/json?";
		if(array_key_exists('geo_latitude',$meta) && array_key_exists('geo_longitude',$meta) && !empty($meta['geo_latitude'][0]) && !empty($meta['geo_longitude'][0])){
			$url = add_query_arg('latlng',$meta['geo_latitude'][0].','.$meta['geo_longitude'][0],$url);
			$process = true;
		}
		elseif(array_key_exists('geo_address',$meta) && !empty($meta['geo_address'][0])){
			$url = add_query_arg('address',$meta['geo_address'][0],$url);
			$process = true;
		}
		if($process){
			$url = add_query_arg('key',get_option('helper_geo_google_apikey'),$url);
			$response = wp_remote_get($url);
			if(is_array($response)){
				$raw = json_decode($response['body']);
				update_post_meta($post_id,'geo_data',json_encode($raw));
				update_post_meta($post_id,'geo_address',$raw->results[0]->formatted_address);
			}
		}
	}
}
$geoHelper_Metabox = new GeoHelper_Metabox();