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
		$this->view->name = 'admin/index';
	}
}
