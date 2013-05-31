<?php

/**
 * Public receiver
 */

namespace ezRPG;

use ezRPG\Library\App,
	ezRPG\Library\Autoloader,
	ezRPG\Library\Container,
	ezRPG\Library\Config;

// Traverse back one directroy
chdir(dirname(__DIR__));

// Bootstrap the application
require dirname(__DIR__) . '/Library/Autoloader.php';

// Instantiate the autoloader
$autoloader = new Autoloader('ezRPG', dirname(__DIR__));
$autoloader->register();

// Instantiate the container for depedancy control
$container = new Container;

// set required dependancies for the container
$config = new Config();
require 'config.php';

$container['config'] = $config;

// Run
try {
	$app = new App($container);
	
	// Set error handler
	set_error_handler(array($app, 'error'), E_ALL | E_STRICT);
	
	$app->run();
	$app->serve();
} catch ( \Exception $e ) {
	if ( !headers_sent() ) {
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
	}

	exit('ezRPG Exception: ' . $e->getMessage());
}