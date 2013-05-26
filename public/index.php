<?php

chdir(dirname(__DIR__));

// Bootstrap the application

require '/Library/Autoloader.php';

$loader = new Library\Autoloader();
$loader->register();

$app = new Library\App();




$app->run();