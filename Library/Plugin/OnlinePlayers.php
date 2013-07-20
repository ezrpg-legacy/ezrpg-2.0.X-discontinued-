<?php

namespace ezRPG\Library\Plugin;
use ezRPG\Library\Interfaces\Plugin;
use ezRPG\Library\Interfaces\Container;

/**
 * OnlinePlayers
 * @see Library\Plugin
 */
class OnlinePlayers implements Plugin 
{
	private $_container;
	
	public function __construct(Container $container) 
	{
		$this->_container = $container;
	}
	
	public function actionBefore()
	{
		// When we have db
		// $playerModel = $this->_container['app']->getModel('Player');
		//var_dump($playerModel->getOnline());
		$this->_container['view']->set('ONLINE', number_format(rand(0,PHP_INT_MAX / 1000000)));
	}
}