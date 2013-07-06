<?php

/**
 * Public receiver
 */

namespace ezRPG;

use ezRPG\Library\App,
	ezRPG\Library\Autoloader,
	ezRPG\Library\Container,
	ezRPG\Library\Config;


$rootPath = dirname(__DIR__);

// Traverse back one directory
chdir($rootPath);

// Bootstrap the application

require $rootPath . '/Library/Autoloader.php';

$autoloader = new Autoloader('ezRPG', dirname(__DIR__));
$autoloader->register();

$container = new Container();

// Set required dependencies
$config = new Config();
require 'config.php';

$container['config'] = $config;

// Run
try {
	$app = new App($container);
	
	set_error_handler(array($app, 'error'), E_ALL | E_STRICT);
	
	$app->run();
	$app->serve();
} catch ( \Exception $e ) {
	if ( !headers_sent() ) {
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
	}

	printf('<strong>ezRPG Exception</strong><br />%s<pre>', $e->getMessage());
	var_dump($e);
	echo '</pre>';  
	
	exit(1);
}