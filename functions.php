<?php

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