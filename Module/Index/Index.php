<?php

namespace ezRPG\Module\Index;
use ezRPG\Library\Module;

/**
 * Deafult Index
 * @see Library\Module
 */
class Index extends Module
{
	/**
	 * Default Action
	 * @param array $params
	 */
	public function index($params) 
	{
	 $this->view->name = 'index';
	 
	 if ($params['act'] == 'hello') {
	 	$this->hello();
	 }
	}
	
	/**
	 * Hello
	 * Used for testing
	 */
	public function hello() 
	{
	 $this->view->name = 'index';
	 $this->view->setMessage('<strong>Information</strong><br />Hello there, Kitty.', 'info');
	}
}
