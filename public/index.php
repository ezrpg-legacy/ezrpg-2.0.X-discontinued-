<?php

chdir(dirname(__DIR__));

// Bootstrap the application

require '/Library/Autoloader.php';

$loader = new Library\Autoloader();
$loader->register();

$app = new Library\App();

//Create an instance of Config class
$config = new Library\Config();

//Now include the actual configuration
require 'config.php';

echo $config['database']['username']; //testing




$app->run();