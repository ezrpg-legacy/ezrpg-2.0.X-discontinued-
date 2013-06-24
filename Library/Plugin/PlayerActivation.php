<?php

namespace ezRPG\Library\Plugin;
use ezRPG\Library\Interfaces\Plugin,
ezRPG\Library\Interfaces\Container;

class PlayerActivation implements Plugin {

	protected $container;

	public function __construct(Container $container) {
		$this->container = $container;
	}

	public function playerActivation($data) {
		// does the application have an activation system enabled
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
	
	protected function sendActivationLink($data){
		// I do nothing, yet.
		return;
	}
}
