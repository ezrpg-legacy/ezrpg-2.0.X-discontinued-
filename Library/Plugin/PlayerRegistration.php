<?php

namespace ezRPG\Library\Plugin;
use ezRPG\Library\Interfaces\Plugin,
ezRPG\Library\Interfaces\Container;

class PlayerRegistration implements Plugin {

	protected $container;


	public function __construct(Container $container) {
		$this->container = $container;
	}

	public function playerRegistration($data) {
		//I do nothing yet.
		return;
	}
	
	public function playerLogin($data) {
		$session = $this->container['app']->getModel('Session');
		$session->clear();
		$session->set('playerid', $data['id']);
		$session->set('hash', $session->generateSignature());
		$session->set('last_active', time());
		header('Location: Home');
		exit;
	}
	
}
