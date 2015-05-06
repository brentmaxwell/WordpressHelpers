<?php

class Helper_Settings{
	public function __construct(){
		add_action( 'admin_init', array( $this , 'settings_init' ) );
		add_action( 'admin_menu', array( $this , 'admin_menu' ) );
	}
	
	public function admin_menu(){
		add_submenu_page(
			'options-general.php',
			__('Helper'),
			__('Helper'),
			'manage_options',
			'helper_settings',
			array($this,'settings_page')
		);
	}
	
	public function settings_page(){
		?>
		<div class="wrap">
			<h2><?php _e( 'Helper' );?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'helper_settings' ); ?>
				<?php do_settings_sections( 'helper_settings' );?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
		
	function settings_init() {
		register_setting('helper_settings','helper_main_enableapi');
		register_setting('helper_settings','helper_geo_google_apikey');
		
		add_settings_section(
			'helper_settings_section_main',
			__( 'Main'),
			array( $this, 'helper_settings_section_main_callback' ),
			'helper_settings'
		);
		
		add_settings_section(
			'helper_settings_section_geo',
			__( 'Geo Settings'),
			array( $this, 'helper_settings_section_geo_callback' ),
			'helper_settings'
		);
		
		add_settings_field(
			'helper_main_enableapi',
			__( 'Enable API Functions'),
			array( $this, 'helper_main_enableapi_callback' ),
			'helper_settings',
			'helper_settings_section_main'
		);
		
		add_settings_field(
			'helper_geo_google_apikey',
			__( 'Google Maps API Key'),
			array( $this, 'helper_geo_google_apikey_callback' ),
			'helper_settings',
			'helper_settings_section_geo'
		);
	}
	
	function helper_settings_section_main_callback() {
	}
	
	function helper_settings_section_geo_callback() {
	}
	
	function helper_main_enableapi_callback() {
		?>
			<input name="helper_main_enableapi" id="helper_main_enableapi" type="checkbox" value="1" <?php checked(get_option( 'helper_main_enableapi'), 1 ); ?> />
		<?
	}
	
	function helper_geo_google_apikey_callback() {
		?>
			<input name="helper_geo_google_apikey" id="helper_geo_google_apikey" type="text" value="<?php echo get_option( 'helper_geo_google_apikey'); ?>" />
		<?
	}
}
$helper_Settings = new Helper_Settings();