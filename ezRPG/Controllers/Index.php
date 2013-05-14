<?php

namespace ezRPG\Controllers;

class Index extends \ezRPG\Controller
{
	protected
		$title = 'Home';

	/**
	 * Default action
	 */
	public function index()
	{
		$checkUser = $this->app->getSingleton('session')->loggedIn();
		$this->view->set('loggedIn', $checkUser);
		if ($checkUser === false ){
			$this->view->name = 'index';
		} else {
			$userID = $this->app->getSingleton('session')->get('userid');
			$this->view->name = 'home';
			$this->view->set('player', $this->app->getSingleton('auth')->getUser($userID));
		}
	}
}
 