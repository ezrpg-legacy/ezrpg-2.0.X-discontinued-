<?php

namespace ezRPG\Module\Admin\Player;
use ezRPG\Library\Module;

class Index extends Module
{

	/**
	 * Login
	 */
	public function index()	{
		return;
	}
	
	public function listing(){
		$data = $this->container['app']->getModel('Player')->findAll();
		$this->container['view']->set('players', $data);
		$this->view->name = 'admin/player/listing';
	}
}
