<?php

class GeoHelper_AdminColumns{
	public function __construct(){
		add_filter(    'manage_posts_columns'       , array( $this , 'add_columns'     ) );
		add_action(    'manage_posts_custom_column' , array( $this , 'display_columns' ) , 10 , 2 );
	}
	
	public function add_columns($columns){
		$columns['coordinates'] = "Coordinates";
		$columns['address'] = 'Address';
		$columns['map'] = 'Map';

		return $columns;
	}
	
	public function display_columns($column_name,$id){
		switch($column_name){
			case 'map':
				$meta = get_post_meta($id);
				if(array_key_exists('geo_latitude',$meta) && array_key_exists('geo_longitude',$meta) && !empty($meta['geo_latitude'][0]) || !empty($meta['geo_longitude'][0])){
					$shortcode = '[staticmap height="100" width="100"';
					$shortcode .= ' markers="color:blue|'.$meta['geo_latitude'][0].','.$meta['geo_longitude'][0].'"';
					if(array_key_exists('geo_polyline',$meta)){
						$shortcode .= ' polyline="color:0xFF0000BF|weight:2|enc:'.urlencode($meta['geo_polyline'][0]).'"';
					}
					$shortcode .= ']';
					echo '<a href="https://www.google.com/maps/place/'.$meta['geo_latitude'][0].','.$meta['geo_longitude'][0].'" target="_blank">';
					echo do_shortcode($shortcode);
					echo '</a>';
				}
				break;
			case 'coordinates':
				$meta = get_post_meta($id);
				if(array_key_exists('geo_latitude',$meta) && array_key_exists('geo_longitude',$meta) && !empty($meta['geo_latitude'][0]) || !empty($meta['geo_longitude'][0])){
					echo '<a href="https://www.google.com/maps/place/'.$meta['geo_latitude'][0].','.$meta['geo_longitude'][0].'" target="_blank">';
					echo $meta['geo_latitude'][0].'<br/>'.$meta['geo_longitude'][0];
					echo '</a>';
				}
				break;
			case 'address':
				$address = get_post_meta($id,'geo_address',true); 
				echo '<a href="https://www.google.com/maps/place/'.$address.'" target="_blank">';
				echo $address;
				echo '</a>';
				break;
		}
	}
}
$geoHelper_AdminColumns = new GeoHelper_AdminColumns();