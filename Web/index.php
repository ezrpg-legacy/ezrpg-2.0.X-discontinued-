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
if ( file_exists('config.php') && filesize('config.php') != 0 ) {
	require 'config.php';
	require 'settings.php';
}

if ( !isset($config['routes']) || substr($_SERVER['PHP_SELF'], 0, 9) == "installer") {
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
}

if ( !isset($config['site']) ) {
	$config['site'] = array(
		'url' => 'http://'.$_SERVER['HTTP_HOST'].str_ireplace($_SERVER['PHP_SELF'], '/index.php', ''),
		'theme' => 'default',
	);
}

if( (!file_exists('config.php')
		|| filesize('config.php') == 0)
		&& !isset($_GET['q'])
		|| substr($_GET['q'], 0, 9) != "installer") {
	if ( !headers_sent() ) {
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
	}
	
	print('<strong>ezRPG Exception</strong><br />ezRPG has not yet been installed.');
	die();
}

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