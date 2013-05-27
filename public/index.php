<?php

chdir(dirname(__DIR__));

// Bootstrap the application

require '/Library/Autoloader.php';

$loader = new Library\Autoloader();
$loader->register();

$container = new Library\Container();

$container['config'] = $container->share(function ($container) {
    // Create an instance of Config class
    $config = new Library\Config();

    // Now include the actual configuration
    require 'config.php';
    
    return $config;
});



$app = new Library\App($container);






$app->run();