<?php

namespace ezRPG\Module\Register;
use ezRPG\Library\Module;

class Index extends Module
{
	/**
	 * Default action
	 */
	public function index() 
	{
		$auth = $this->app->getModel('player');
		
		if (isset($_POST['register'])) {
			$insert = array();
			$insert['username'] = $_POST['username'];
			$insert['email'] =	$_POST['email'];
			$insert['password'] = $_POST['password'];
			
			// attempt to register the account
			try {
				$register = $auth->create($insert);
			} catch(\Exception $e) {
				$this->container['view']->setMessage($e->getMessage(), 'warn');
			}
			
			// if the account is active, redirect the user to the login page
			if (isset($register['active']) && $register['active'] == '1') {
				$this->container['view']->setMessage('Your accounts was successfully created. You may now log in.', 'success');
				$this->view->name = 'index';
			} elseif (isset($register['active']) && $register['active'] == '0') {
				$this->container['view']->setMessage('Your account has been created, but requires activation.', 'success');
				$this->view->name = 'index';
			}
		}
	}
}