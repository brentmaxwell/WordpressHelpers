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
		
		
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'geo_metabox', 'geo_metabox_nonce' );

		$meta = get_post_meta($post->ID);
		echo "<tr>";
		echo $this->generate_metabox_field('geo_public',$meta['geo_public'][0],'Public');
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
			)
		);
		echo '<img src="'.$mapsUrl.'"/>';
	}
	
	public function generate_metabox_field($meta_id,$meta_value,$label){
		$output =  '<td><label for="'.$meta_id.'">'.
		__( $label, 'myplugin_textdomain' ).
		'</label></td><td>'.
		'<input type="text" id="'.$meta_id.'" name="'.$meta_id.'"'.
        ' value="' . esc_attr( $meta_value ) . '"/></td>';
		return $output;
	}
	
	public function mapsUrl($location,$size){
		$baseUrl = 'https://maps.google.com/maps/api/staticmap?maptype=terrain';
		$baseUrl .= '&amp;size='.$size['width'].'x'.$size['height'];
		$baseUrl .= '&amp;sensor=false';
//		$baseUrl .= '&amp;center='.$location['lat'].','.$location['lng'];
		$baseUrl .= '&amp;markers=color:red%7C'.$location['lat'].','.$location['lng'];
		return $baseUrl;
	}
}

$geoMetaBox = new GeoMetaBox();