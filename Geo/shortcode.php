<?php

class GeoHelper_Shortcode{
	public function __construct(){
		add_shortcode( 'staticmap' , array( $this , 'do_shortcode' ) );
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
				'key'             => get_option('geohelper_google_apikey')
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
}
$geoHelper_Shortcode = new GeoHelper_Shortcode();