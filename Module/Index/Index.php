<?php

namespace ezRPG\Module\Index;
use ezRPG\Library\Module;

class Index extends Module
{
    public function index() 
    {
     $this->view->name = 'index';
    }
    
	public function hello() 
	{
     $this->view->name = 'index';
     $this->view->setMessage('<strong>Information</strong><br />Hello there, Kitty.', 'info');
	}
}
