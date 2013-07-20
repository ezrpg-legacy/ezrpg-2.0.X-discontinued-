<?php

namespace ezRPG\Library\Plugin;
use ezRPG\Library\Interfaces\Plugin;
use ezRPG\Library\Interfaces\Container;

/**
 * PlayerActivation
 * @see Library\Plugin
 */
class PlayerActivation implements Plugin 
{
	protected $container;

	public function __construct(Container $container) 
	{
		$this->container = $container;
	}

	/**
	 * playerActivation
	 * @param array $data
	 * @return int
	 */
	public function playerActivation($data) 
	{
		/* Does the application have an activation system enabled */
		$configRequireActivation = $this->container['config']['accounts']['requireActivation'];
		if ($configRequireActivation) {
			$data['active'] = 0;
				
			if ($this->container['config']['accounts']['emailActivation']) {
				$this->sendActivationLink($data);
			}
		} else {
			$data['active'] = 1;
		}
		
		return $data['active'];
	}
	
	/**
	 * sendActivationLink
	 * Not yet implemented
	 * @param array $data
	 * @todo Finish implementing
	 */
	protected function sendActivationLink($data)
	{
		return;
	}
}
