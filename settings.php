<?php


$config['router'] = array(
		'partialRoutes' => true,
		'routes' => array()
);

$config['security'] = array(
		'hashRounds' => 11,
		'hashSaltSize' => 16,
		'passwordStrength' => 1,
		'login'	=> array(
				'showInvalidLoginReason' => true,
				'returnUsernameOnFailure' => true
		)
);

$config['accounts'] = array(
		'requireActivation' => false,
		'emailActivation' => false
);


$config['routes'] = array(
		'installer'	=> array(
				'module' => 'installer',
		),

		'admin' => array(
				'module' => 'admin',
		),

		'admin/player/listing' => array(
				'base' => 'admin',
				'module' => 'player',
				'action' => 'listing'
		),

		'admin/player' => array(
				'base' => 'admin',
				'module' => 'player'
		),

		'index(.*)' => array(
				'module' => 'index',
				'type' => 'regex',
				'params' => array('act')
		),

		'error' => array(
				'module' => 'error404',
				'action' => 'index'
		),

		'player/([a-z]+)' => array(
				'module' =>	'player',
				'action' => 'view',
				'params' => array('username'),
				'type' => 'regex'
		),

		'login' => array(
				'module' => 'login',
		),

		'register' => array(
				'module' => 'register',
		),

		'home' => array(
				'module' => 'home',
		),
);