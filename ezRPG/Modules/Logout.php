<?php

namespace ezRPG\Modules;

class Logout extends \ezRPG\Module
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
