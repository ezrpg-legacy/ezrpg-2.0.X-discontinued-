<?php

namespace ezRPG;

try {
	chdir(dirname(__FILE__));

	// Bootstrap the application
	require 'ezRPG/Interfaces/App.php';
	require 'ezRPG/App.php';

	$app = new App;

	set_error_handler(array($app, 'error'), E_ALL | E_STRICT);

	spl_autoload_register(array($app, 'autoload'));

	require 'config.php';

	$app->run();
	$app->serve();
} catch ( \Exception $e ) {
	if ( !headers_sent() ) {
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
	}

	exit('ezRPG Exception: ' . $e->getMessage());
}
