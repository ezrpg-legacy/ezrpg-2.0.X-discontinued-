<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;

/**
 * Route
 * @see Library\Model
 */
class Route extends Model
{
	protected $tableName = 'route';
	
	/**
	 * Retrieve all routes as an associative array
	 * @return multitype:string
	 */
	public function getAll() 
	{
		$routes = $this->query('SELECT * FROM <prefix>route');
		$routes = $routes->fetchAll();
		
		return $routes;
	}

	/**
	 * Builds cache of routes
	 * @return boolean
	 */
	public function buildCache()
	{
		$routes = $this->getAll();
		$routeCache = array();
		
		foreach($routes as $route) {
			$currentRoute = array();
			
			// TODO please implement a proper builder
			!!!$route['base'] || $currentRoute['base'] = $route['base'];
			!!!$route['module'] || $currentRoute['module'] = $route['module'];
			!!!$route['action'] || $currentRoute['action'] = $route['action'];
			!!!$route['type'] || $currentRoute['type'] = $route['type'];
			!!!$route['params'] || $currentRoute['params'] = explode(',', $route['params']);
			!!!$route['access'] || $currentRoute['access'] = explode(',', $route['access']);
			
			$routeCache[$route['path']] = $currentRoute;
		}
		
		return $routeCache;
	}
}