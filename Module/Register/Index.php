<?php

namespace ezRPG\Module\Register;
use ezRPG\Library\Module;

class Index extends Module
{
	/**
	 * Default action
	 */
	public function index() {
		$fail = '';
		$warn = '';
		$auth = $this->app->getModel('auth');
		if (isset($_POST['register'])) {
			if (strlen($_POST['username']) < 3) {
				$warn = 'Your username must be at least 3 characters long.';
			}
			if (strlen($_POST['username']) > 25) {
				$warn = 'Your username cannot be greater than 25 characters long.';
			}
			if (strlen($_POST['password']) < 3) {
				$warn = 'Your username must be at least 3 characters long.';
			}
			if (strlen($_POST['password']) > 25) {
				$warn = 'Your username cannot be greater than 25 characters long.';
			}
			if (strlen($_POST['email']) < 3 || !preg_match("/^[-!#$%&\'*+\\.\/0-9=?A-Z^_`{|}~]+@([-0-9A-Z]+\.)+([0-9A-Z]){2,4}$/i", $_POST['email'])) {
				$warn = 'Your email is using an invalid format.';
			}
			
			if (!$warn) {
				//register($player = '', $email = '', $password = '')
				$register = $auth->register($_POST['username'], $_POST['email'], $_POST['password']);
				if (!$register) {
					$fail = 'Unable to register.';
					$this->view->name = 'register';
				}
			}else{
				$this->view->name = 'register';
			}
		} else {
			$this->view->name = 'register';
		}
	}
}