<?php

class GeoHelpers{
	public function __construct(){
		add_filter(    'manage_posts_columns'       , array( $this , 'add_columns'     ) );
		add_action(    'manage_posts_custom_column' , array( $this , 'display_columns' ) , 10 , 2 );
		add_action(    'add_meta_boxes'             , array( $this , 'add_metabox'     ) );
		add_action(    'save_post'                  , array( $this , 'save_metabox'    ) );
		add_action(    'admin_init'                 , array( $this , 'settings_init'   ) );
		add_shortcode( 'staticmap'                  , array( $this , 'do_shortcode'    ) );
	}
	
	public function do_shortcode( $args ){
		$args = shortcode_atts(array(
				'center'          => null,
				'polyline'        => null,
				'polyline_color'  => '0xFF0000BF',
				'polyline_weight' => 2,
				'markers'         => null,
				'height'          => 240,
				'width'           => 240,
				'maptype'         => 'terrain',
				'scale'           => 1,
				'zoom'            => 0,
				'class'           => null,
				'key'             => get_option('geohelper_google_api_key')
			),
			$args,
			'staticmap'
		);
		if(!is_null($args['markers']) || !is_null($args['polyline'])){
			$baseUrl = 'https://maps.google.com/maps/api/staticmap?';
			$baseUrl .= 'maptype='.$args['maptype'];
			$baseUrl .= '&amp;scale='.$args['scale'];
			$baseUrl .= '&amp;size='.$args['width'].'x'.$args['height'];
			if($args['zoom'] > 0){
				$baseUrl .= '&zoom='.$args['zoom'];
			}
			if(!empty($args['center'])){
				$baseUrl .= '&center='.$args['center'];
			}
			$baseUrl .= '&amp;sensor=false';
			$baseUrl .= '&amp;key='.$args['key'];
			
			
			if($args['markers'] != null)
			{
				$markers = explode(';',$args['markers']);
				
				foreach($markers as $marker)
				{
					$baseUrl .= '&amp;markers=';
					$baseUrl .= $marker .'|';
				}
			}
			if($args['polyline'] != null){
				$baseUrl .= '&amp;path=color:'.$args['polyline_color'].'|weight:'.$args['polyline_weight'].'|enc:'.urldecode($args['polyline']);	
			}
			$output = '<img';
			if($args['class'] != null){
				$output .= ' class="'.$args['class'].'"';
			}
			$output .= ' src="'.$baseUrl.'"/>';
			return $output;
		}
	}
	
	public function add_columns($columns){
		$columns['geo'] = 'Map';
		return $columns;
	}
	
	public function display_columns($column_name,$id){
			if($column_name == 'geo'){
				$meta = get_post_meta($id);
				if(array_key_exists('geo_latitude',$meta) && array_key_exists('geo_longitude',$meta)){
					if( !empty($meta['geo_latitude'][0]) || !empty($meta['geo_longitude'][0]) ){
						$shortcode = '[staticmap height="100" width="100"';
						$shortcode .= ' markers="color:blue|'.$meta['geo_latitude'][0].','.$meta['geo_longitude'][0].'"';
						if(array_key_exists('geo_polyline',$meta)){
							$shortcode .= ' polyline="color:0xFF0000BF|weight:2|enc:'.urlencode($meta['geo_polyline'][0]).'"';
						}
						$shortcode .= ']';
						echo do_shortcode($shortcode);
					}
				}
			}
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
	
	public function render_map_static($args){
			$base_url = 'https://maps.google.com/maps/api/staticmap?';
			$params = array(
				'maptype' =>'terrain',
				'sensor' => 'false',
				'size' => $args['size']['width'].'x'.$args['size']['height'],
				'markers' => 'color:red|'.$args['lat'].','.$args['lng'],
				'key' => get_option( 'geo_metabox_google_api_key')
			);
			if(array_key_exists('polyline',$args)){
				$params['path'] = urldecode($args['polyline']);
				$params['scale'] = 1;
			}
			$base_url .= http_build_query($params);
			echo '<img src="'.$base_url.'"/>';
		}
	
	public function mapsUrl($location,$size){
		
	}
	
	public function render_map_dynamic($location,$size){
		echo '<img src="'.$this->mapsUrl($location,$size).'"/>';
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
		
		update_post_meta( $post_id, 'geo_latitude', $geo_latitude );
		update_post_meta( $post_id, 'geo_longitude', $geo_longitude );
		update_post_meta( $post_id, 'geo_public', $geo_public );	
	}
	
	
	function settings_init() {
		add_settings_section(
			'geohelper_settings',
			__( 'Geo Helper'),
			array( $this, 'settings_section_callback' ),
			'writing'
		);
		
		add_settings_field(
			'geohelper_google_api_key',
			__( 'Google Maps API Key'),
			array( $this, 'setting_option_callback_apikey' ),
			'writing',
			'geohelper_settings'
		);
		
		register_setting(
			'writing',
			'geohelper_google_api_key'
		);
	}
	
	function settings_section_callback() {
		?><p><?php _e( 'Geo Helper Options'); ?></p><?php
	}
	function setting_option_callback_apikey() {
		?>
			<input name="geohelper_google_api_key" id="geohelper_google_api_key" type="text" value="<?php echo get_option( 'geohelper_google_api_key'); ?>" />
		<?
	}
}
$myActiveLife_AdminMeta_GeoMetaBox = new MyActiveLife_AdminMeta_GeoMetaBox();