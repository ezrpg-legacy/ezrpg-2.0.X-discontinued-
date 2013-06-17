<?php

namespace ezRPG\Module\AclTest;
use ezRPG\Library\Module,
	ezRPG\Library\AccessControl;

class Index extends Module
{
	protected
		$title = 'Access Controll - Test'
		;

	/**
	 * Default action
	 */
	public function index()
	{
		$ac = new AccessControl($this->container);
		$stack = array();
		$ac->setPlayer(1);
		
		$stack = array(
			'is_root'	=> $ac->hasRole('root'),
			'can_kitty' => $ac->validate('kitty'),
			'can_hello' => $ac->validate('hello')
		);
		
		
		$this->view->set('stack', $stack);
	}
}