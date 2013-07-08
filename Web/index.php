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

// Set required dependencies
$container = new Container();

$config = new Config();
require 'config.php';
require 'settings.php';

$container['config'] = $config;

// Run
try {
	$app = new App($container);
	
	set_error_handler(array($app, 'error'), E_ALL | E_STRICT);
	
	$app->run();
	$app->serve();
} catch ( \Exception $e ) {
	if ($config['security']['showExceptions']) {
		printf('<div><strong>ezRPG Exception</strong></div>%s<pre>', $e->getMessage());
		var_dump($e);
		echo '</pre>';
	} else {
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
		
		echo '<!DOCTYPE html><html><body><h3>Service Temporarily Unavailable</h3>Please try again later.</body></html>';
	}
	
	exit(1);
}