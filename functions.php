<?php
/**
 * @param $obj
 */
function pr($obj) {
	echo '<pre>';
	ob_start('htmlentities');
	print_r($obj);
	ob_end_flush();
	echo '</pre>';
}
/**
 * @param $obj
 */
function vd($obj) {
	echo '<pre>';
	ob_start('htmlentities');
	var_dump($obj);
	ob_end_flush();
	echo '</pre>';
}

spl_autoload_register( function ( $class ) {
	$file = realpath(
		__DIR__ . DIRECTORY_SEPARATOR .
		str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php'
	);
	if ( $file ) {
		require_once $file;
	}
} );

Royal\Engine::getInstance();