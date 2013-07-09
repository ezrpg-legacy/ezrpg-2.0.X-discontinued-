<?php

/**
 * Public receiver
 */

namespace ezRPG;

use \ezRPG\Library\App,
	\ezRPG\Library\Autoloader,
	\ezRPG\Library\Container,
	\ezRPG\Library\Config;
error_reporting(E_ALL);

$rootPath = dirname(__DIR__);

// Traverse back one directory
chdir($rootPath);

// Bootstrap the application
require 'Library/Autoloader.php';
$autoloader = new Autoloader('ezRPG', dirname(__DIR__));
$autoloader->register();

// Set required dependencies
$container = new Container();

$config = new Config();

if (!file_exists('config.php')) {
	$config['security'] = array(
		'acl' => array('use' => false),
		'showExceptions' => true
	);
	
	$config['routes'] = array(
			'installer'	=> array(
					'module' => 'installer',
			),
			'installer/license'	=> array(
					'module' => 'license',
					'base' => 'installer',
			),
			'installer/config'	=> array(
					'module' => 'config',
					'base' => 'installer',
			),
			'installer/structure'	=> array(
					'module' => 'structure',
					'base' => 'installer',
			),
			'installer/admin'	=> array(
					'module' => 'admin',
					'base' => 'installer',
			),
	);

	$config['site'] = array(
			'url' => 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']),
			'theme' => 'installer',
	);
	
	if(stripos($_GET['q'], 'installer') !== 0) {
		echo 'ezRPG has not been installed yet.<br />';
		echo '<a href="./installer">Install ezRPG</a>';
		exit(0);
	}
} else {
	require 'config.php';
	require 'settings.php';
}

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