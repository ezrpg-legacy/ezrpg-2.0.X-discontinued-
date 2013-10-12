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
$config = new Config($container);
$uri = new Library\Uri();

if (!file_exists('config.php') || $uri->segment(0) == "installer") {
	define('INSTALL', true);
	if ($uri->segment(1) != "admin") {
		define('NO_HOOKS', true);
	}
	require 'config.install.php';
} else {
	if (file_exists("settings.php")) {
		require 'settings.php';
	}
	if (file_exists("routes.php")) {
		require 'routes.php';
	}

	require 'config.php';
}

$container['config'] = $config;

// Run
try {
	$app = new App($container);
	if (!file_exists("settings.php") && !defined('INSTALL')) {
		$settings = $app->getModel('setting');
		try {
			$settings->buildCache();
			require('settings.php');
			$container->offsetSet('config', $config);
		} catch (\Exception $e) {
			printf('<div><strong>ezRPG Exception</strong></div>%s<pre>', $e->getMessage());
			var_dump($e);
			die();
		}
	}
	
	if (!file_exists("routes.php") && !defined('INSTALL')) {
		$routes = $app->getModal('route');
		try {
			$routes->buildCache();
			require 'routes.php';
			$container->offsetSet('config',$config);
		} catch(\Exception $e) {
			printf('<div><strong>ezRPG Exception</strong></div>%s<pre>', $e->getMessage());
			var_dump($e);
			die();
		}
	}
	
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