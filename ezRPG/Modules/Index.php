<?php

namespace ezRPG\Modules;

class Index extends \ezRPG\Module
{
	protected
		$title = 'Home';

	/**
	 * Default action
	 */
	public function index()
	{
		$checkPlayer = $this->app->getSingleton('session')->loggedIn();
		$this->view->set('loggedIn', $checkPlayer);
		if ($checkPlayer === false ){
			$this->view->name = 'index';
		} else {
			$playerID = $this->app->getSingleton('session')->get('playerid');
			$this->view->name = 'home';
			$this->view->set('player', $this->app->getSingleton('auth')->getPlayer($playerID));
		}
	}
	
	public function hello() {
		$this->view->name = 'index';
		$this->view->set('hello', 'Kitty'); 
	}
}
 