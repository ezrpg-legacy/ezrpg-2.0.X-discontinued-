<?php

namespace ezRPG\Module\Index;
use ezRPG\Library\Module;

class Index extends Module
{
	public function index($params) 
	{
	 $this->view->name = 'index';
	 
	 if ($params['act'] == 'hello') {
	 	$this->hello();
	 }
	}
	
	public function hello() 
	{
	 $this->view->name = 'index';
	 $this->view->setMessage('<strong>Information</strong><br />Hello there, Kitty.', 'info');
	}
}
