<?php

class GeoHelper_Settings{
	public function __construct(){
		add_action( 'admin_init', array( $this , 'settings_init' ) );
		add_action( 'admin_menu', array( $this ,'admin_menu' ) );
	}
	
	public function admin_menu(){
		add_submenu_page(
			'options-general.php',
			__('Geo Helper'),
			__('Geo Helper'),
			'manage_options',
			'geohelper_settings',
			array($this,'settings_page')
		);
	}
	
	public function settings_page(){
		?>
		<div class="wrap">
			<h2><?php _e( 'Geo Helper' );?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'geohelper_settings' ); ?>
				<?php do_settings_sections( 'geohelper_settings' );?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
		
	function settings_init() {
		register_setting('geohelper_settings','geohelper_google_apikey');
		
		add_settings_section(
			'geohelper_settings_section',
			__( 'Geo Helper'),
			array( $this, 'geohelper_settings_section_callback' ),
			'geohelper_settings'
		);
		
		add_settings_field(
			'geohelper_google_apikey',
			__( 'Google Maps API Key'),
			array( $this, 'geohelper_google_apikey_callback' ),
			'geohelper_settings',
			'geohelper_settings_section'
		);
		
		
	}
	
	function geohelper_settings_section_callback() {
		?><p><?php _e( 'Geo Helper Options'); ?></p><?php
	}
	function geohelper_google_apikey_callback() {
		?>
			<input name="geohelper_google_apikey" id="geohelper_google_apikey" type="text" value="<?php echo get_option( 'geohelper_google_apikey'); ?>" />
		<?
	}
}
$geoHelper_Settings = new GeoHelper_Settings();