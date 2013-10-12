<?php

namespace ezRPG\Library\Router;

/**
 * Route
 */
abstract class Route
{
	protected $route;
	
	protected $options = array(
		'base' => null,
		'module' => null,
		'action' => 'index',
		'type' => 'literal',
		'access' => array(
			'permission' => null,
			'role' => null,
		),
		'params' => array()
	);

	public function __construct($route, $options) 
	{
		$this->route = $route;
		$this->options = array_merge($this->options, $options);
	}
}