<?php

namespace ezRPG\Module\DevTools;
use ezRPG\Library\Module;
use ezRPG\Library\AccessControl;

/**
 * DevTools Index
 * Various Tools To Test Methods
 * @see Library\Module
 */
class Index extends Module
{
	protected $title = 'Developer Tools';

	/**
	 * Default action
	 */
	public function index()
	{
		$params = $this->container['app']->getParams();
		var_dump($params);
		/* $this->aclTest(); */
	}
	
	/**
	 * dummyAction
	 */
	public function dummyAction() 
	{
		echo 'HAAI';exit;
	}
	
	/**
	 * aclTest
	 */
	public function aclTest()
	{
		$ac = new AccessControl($this->container);
		$stack = array();
		$ac->setPlayer(1);
		
		$stack = array(
			'is_root'	=> $ac->hasRole('root'),
			'can_kitty' => $ac->validate('kitty'),
			'can_hello' => $ac->validate('hello')
		);
		
		$this->view->set('acl_stack', $stack);
	}
	
	/**
	 * setPassword
	 */
	public function setpassword()
	{
		if ($_POST['username'] && $_POST['password']) {
			$playerModel = $this->container['app']->getModel('Player');
			$playerModel->safeMode = false;
				
			$player = $playerModel->find($_POST['username'], 'username');
			$id = $player['id'];
				
			$salt = $playerModel->createSalt();
			$password = $playerModel->createHash($_POST['password'], $salt);
				
			$playerModel->save(array('id' => $id, 'password' => $password, 'salt' => $salt));
				
			$this->view->setMessage('Done', 'info');
		}
		
		$this->index();
	}
}