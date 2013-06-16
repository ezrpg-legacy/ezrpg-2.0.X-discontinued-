<?php

namespace ezRPG\Module\Home;
use ezRPG\Library\Module;

class Index extends Module
{
    public function index() {
		$player = $this->app->getModel('session');
		$checkPlayer = $player->loggedIn();
		$this->view->set('loggedIn', $checkPlayer);
		if ($checkPlayer === false ){
			header('Location: Index');
            exit;
		} else {
			$playerID = $player->get('playerid');
			$this->view->name = 'home';
			$this->view->set('player', $this->app->getModel('auth')->getPlayer($playerID));
		}
    }
	public function test() {
		$this->module->name = Error404;
	}
}
