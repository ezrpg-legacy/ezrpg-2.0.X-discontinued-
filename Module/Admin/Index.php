<?php

namespace ezRPG\Module\Admin;
use ezRPG\Library\Module;

/**
 * Admin Index
 */
class Index extends Module
{

	/**
	 * Login
	 */
	public function index()	
	{
		return;
	}
	
	public function player()
	{
		$this->view->name = 'admin/player/index';
	}
}
