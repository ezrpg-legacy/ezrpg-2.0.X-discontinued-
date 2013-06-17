<?php

namespace ezRPG\Module\Login;
use ezRPG\Library\Module;

class Index extends Module
{

	/**
	 * Login
	 */
	public function index()	{
		$error = null;
		$playerModel = $this->app->getModel('Player');
		$session = $this->app->getModel('Session');
		
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $error = 'Please enter your username and password!';
		} else {
			try {
				$player = $playerModel->authenticate($_POST['username'], $_POST['password']);
			} catch (\Exception $e) {
				$error = 'Invalid username and/or password';
				$error = $e->getMessage();
			}
		}
		
        if (is_null($error)) {
			$session->clear();
// 			$auth->setLastLogin($_POST['username']);
			$session->set('playerid', $player['id']);
            $session->set('hash', $session->generateSignature());
            $session->set('last_active', time());
            header('Location: Home');
            exit;
        } else {
			$session->clear();
			
			$error = 'Sorry, you could not be logged in:<br />' . $error;
			$this->view->setMessage($error, 'fail');
				
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
