<?php
namespace ezRPG;

try {
	chdir(__DIR__);

	// Bootstrap the application
        require 'ezRPG/Autoload.php';
        
        $autoloader = new Autoload;
        
        spl_autoload_register(array($autoloader, 'loadClass'));
        
	$app = new App;

	set_error_handler(array($app, 'error'), E_ALL | E_STRICT);


	require 'config.php';

	$app->run();
	$app->serve();
} catch ( \Exception $e ) {
	if ( !headers_sent() ) {
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
	}

	exit('ezRPG Exception: ' . $e->getMessage());
}
