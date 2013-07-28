<?php

/**
 * Installer Configuration
 * Overload the configuration
 */
$config = array(
	'security' => array(
		'hashRounds' => 11,
		'hashSaltSize' => 16,
		'passwordStrength' => 1,
		'showExceptions' => true,
		'acl' => array(
			'use' => false,
		)
	),
		
	'accounts' => array(
		'requireActivation' => false,
		'emailActivation' => false
	),
		
	'site' => array(
		'url' => 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']),
		'theme' => 'installer',
	),
		
	'routes' => array(
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
			
		'installer/admin'	=> array(
			'module' => 'admin',
			'base' => 'installer',
		),
	)
);

if (file_exists('config.php')) {
	include("config.php");
}

/**
 * Display a friendly message to user to let them know
 * that the engine should be installed before usage.
 */
if ($uri->segment(0) != "installer") {
	echo 'ezRPG has not been installed yet.<br />';
	echo '<a href="./installer">Install ezRPG</a>';
	exit(0);
} else {
	if (file_exists("Module/Installer/locked")) {
		echo 'ezRPG installer is currently locked.<br />';
		echo 'Please remove the Module/Installer/locked file to continue.';
		exit(0);
	}
}