<?php

chdir(dirname(__DIR__));

// Bootstrap the application

require '/Library/Autoloader.php';

$loader = new Library\Autoloader();
$loader->register();

$container = new Library\Container();

$config = new Library\Config();

require 'config.php';

$container['config'] = $config;

// Routing


$app = new Library\App($container);
$app->run();