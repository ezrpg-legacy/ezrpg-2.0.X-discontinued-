<?php

namespace ezRPG\Module\Login;
use ezRPG\Library\Module;

class Index extends Module
{

	/**
	 * Default action
	 */
	public function index()	{
		$fail = '';
		$warn = '';
		$auth = $this->app->getModel('auth');
		$session = $this->app->getModel('session');
		
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $warn = 'Please enter your username and password!';
		} else {
			try {
				$player = $auth->authenticate($_POST['username'], $_POST['password']);
			} catch (\Exception $e) {
				$fail = 'Invalid username and/or password';
			}
		}
		
        if (!$fail && !$warn) {
			$session->clear();
			$auth->setLastLogin($_POST['username']);
			$session->set('playerid', $player->id);
            $session->set('hash', $session->generateSignature());
            $session->set('last_active', time());
            header('Location: Home');
            exit;
        } else {
			$session->clear();
			
			if (!empty($warn)) {
				$this->view->setMessage($warn, 'warn');
			} else {
				$msg = 'Sorry, you could not be logged in:<br />' . $fail;
				$this->view->setMessage($msg, 'fail');
			}
			// Changed from a header to just grabbing the view itself
            $this->view->name = "index";
        }
		
	}
	
	public function logout(){
		$session = $this->app->getModel('session');
		$session->clear();
        header('Location: Index');
        exit;
	}
}
