<?php
if(get_option('helper_main_enableapi') == 1){
	$dir = glob( dirname( __FILE__ ) . "/Api/*.php" );
	foreach ( $dir as $file ){
		require $file;
	}
}