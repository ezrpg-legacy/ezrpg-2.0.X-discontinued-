<?php

namespace ezRPG\Library\Plugin;
use ezRPG\Library\Interfaces\Plugin;
use ezRPG\Library\Interfaces\Container;

/**
 * PlayerRegistration
 * @see Library\Plugin
 */
class PlayerRegistration implements Plugin 
{
	protected $container;

	public function __construct(Container $container) 
	{
		$this->container = $container;
	}

	/**
	 * playerRegistration
	 * @param array $data
	 * @todo Isnt implemented yet
	*/
	public function playerRegistration($data) 
	{
		return;
	}
	
	/**
	 * playerLogin
	 * @param array $data
	*/
	public function playerLogin($data) 
	{
		$session = $this->container['app']->getModel('Session');
		$session->clear();
		$session->set('playerid', $data['id']);
		$session->set('hash', $session->generateSignature());
		$session->set('last_active', time());
		header('Location: Home');
		exit;
	}
	
}
