<?php

namespace ezRPG\Module\Index;
use ezRPG\Library\Module;

class Index extends Module
{
    public function index() {
      $this->view->name = 'index';

      $player = $this->app->getModel('Player');

      $player->test();
    }
	public function hello() {

	}
}
