<?php

class GeoMetaBox{
	public function __construct(){
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
	}
	
	public function add_meta_box(){
		add_meta_box(
			'geo',
			__('Geo'),
			array($this,'render_meta_box'),
			null,
			'side'
		);
	}
	
	public function render_meta_box($post,$metabox){
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
		echo "</tbody>";
		echo "</table>";
		$mapsUrl = $this->mapsUrl(array(
			'lat' => $meta['geo_latitude'][0],
			'lng' => $meta['geo_longitude'][0]),
			array(
				'height' => 254,
				'width' => 254
			));
		echo '<img src="'.$mapsUrl.'"/>';
	}
	
	public function generate_metabox_field($meta_id,$meta_value,$label,$type="text"){
		$output =  '<td><label for="'.$meta_id.'">';
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
		$output .= '"/></td>';
        
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

		$geo_latitude = sanitize_text_field( $_POST['geo_latitude'] );
		$geo_longitude = sanitize_text_field( $_POST['geo_longitude'] );
		$geo_public = $_POST['geo_public'];
		update_post_meta( $post_id, 'geo_latitude', $latitude );
		update_post_meta( $post_id, 'geo_longitude', $geo_longitude );
		update_post_meta( $post_id, 'geo_public', $geo_public );
	}
	
	public function mapsUrl($location,$size){
		$baseUrl = 'https://maps.google.com/maps/api/staticmap?maptype=terrain';
		$baseUrl .= '&amp;size='.$size['width'].'x'.$size['height'];
		$baseUrl .= '&amp;sensor=false';
		$baseUrl .= '&amp;markers=color:red%7C'.$location['lat'].','.$location['lng'];
		return $baseUrl;
	}
}
$geoMetaBox = new GeoMetaBox();