<?php

namespace ezRPG\Module\Installer\Admin;
use ezRPG\Library\Module;

/**
 * Admin Index
 * @see Library\Module
 */
class Index extends Module
{
	/*
	 * Default Action
	 */
	public function index()
	{
		$auth = $this->app->getModel('player');
		
		if (isset($_POST['submit'])) {
			$insert = array();
			$insert['username'] = $_POST['username'];
			$insert['email'] =	$_POST['email'];
			$insert['password'] = $_POST['password'];
			$insert['confirm_password'] = $_POST['password'];
			
			try {
				/* Attempt to register the account */
				$register = $auth->create($insert);
			} catch(\Exception $e) {
				die($e);
				$message = '<strong>You could not be registered:</strong><ul>';
				foreach(unserialize($e->getMessage()) as $prev) {
					$message .= '<li>' . $prev->getMessage() . '</li>';
				}
				$message .= '</ul>';
				
				$this->container['view']->setMessage($message, 'warn');
			}
			
			/* If the account is active, redirect the user to the login page */
			if (isset($register['active'])) {
				$playerRole = $this->app->getModel('playerRole');
				$playerRole->addRole($register['id'], 1);
				
				if ( is_writable("Module/Installer/") ) {
					$fh = fopen("Module/Installer/locked", 'w+');
					
					if ( !$fh ) {
						die('Your admin account was created, but we were unable to lock the installer. Please remove the Module/Installer directory to use your game.');
					} else {
						die('Your admin account was created, and the installer was locked! Continue to your game');
					}
				
				} else {
					die('Your admin account was created, but we were unable to lock the installer. Please remove the Module/Installer directory to use your game.');
				}
				
			}
			
		}
		$this->view->name = 'admin';
	}
}
