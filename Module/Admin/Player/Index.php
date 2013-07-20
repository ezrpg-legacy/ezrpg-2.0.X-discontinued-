<?php

namespace ezRPG\Module\Admin\Player;
use ezRPG\Library\Module;

/**
 * Player Index
 */
class Index extends Module
{

	/**
	 * Default Action
	 */
	public function index()	{
		return;
	}
	
	/**
	 * Listing
	 */
	public function listing(){
		$data = $this->container['app']->getModel('Player')->findAll();
		$this->container['view']->set('players', $data);
		$this->view->name = 'admin/player/listing';
	}
}
