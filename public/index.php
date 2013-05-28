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
require 'config.php';
require dirname(__DIR__) . '/Library/Autoloader.php';

// Instantiate the autoloader
$autoloader = new Autoloader('ezRPG', dirname(__DIR__));
$autoloader->register();

// Instantiate the container for depedancy control
$container = new Container;

// set required dependancies for the container
$config = new Config;
$config['database'] = $userland_config['database'];

$container['config'] = $config;

// Run
$app = new App($container);
$app->run();