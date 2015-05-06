<?php

$dir = glob( dirname( __FILE__ ) . "/Geo/*.php" );
foreach ( $dir as $file ){
	require $file;
}