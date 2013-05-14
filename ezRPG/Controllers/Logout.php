<?php

namespace ezRPG\Controllers;

class Logout extends \ezRPG\Controller
{
	protected
		$title = 'Home';

	/**
	 * Default action
	 */
	public function index()
	{
		$session = $this->app->getSingleton('session');
		$session->clear();
        header('Location: index');
        exit;
	}
}
