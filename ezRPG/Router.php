<?php

namespace ezRPG;
use ezRPG\Interfaces\Router as RouterInterface,
	\ReflectionClass;

/**
 * Router
 *
 */
class Router implements RouterInterface {
	
	/**
	 * An associative array containing current routes
	 * @var array
	 */
	public $_routes = array();
	
	/**
	 * Inject multiple routes
	 * @param array $routes
	 */
	public function addRoutes(array $routes) {
		
		foreach($routes as $key => $route) {
			$this->addRoute(array($key => $route));
		}
	}
	
	/**
	 * Retrieve multiple routes
	*/
	public function getRoutes() {
		
		return $this->_routes;
	}
	
	/**
	 * Inject a single route
	 * @param array $route
	*/
	public function addRoute($route) {
		
		$this->_routes = array_merge($this->_routes, $route);
	}
	
	/**
	 * Retrieves a single route by URI
	 * @param string $path
	*/
	public function getRoute($route) {
		
		if (array_key_exists($route, $this->_routes)) {
			return $this->_routes[$route];
		}
		
		return false;
	}
	
	/**
	 * Automatically add routes by directory
	 * @param string $directory
	*/
	public function buildRoutes($directory) {
		
		if (!is_dir($directory)) {
			throw new \InvalidArgumentException('"' . $directory . '" is not a directory');
		}
		
		$entries = scandir($directory, SCANDIR_SORT_NONE);
		$routes = array();
		
		foreach ($entries as $entry) {
			
			$entry = $directory . DIRECTORY_SEPARATOR . $entry;
			if (!(strtolower(substr($entry, strrpos($entry, '.'))) == '.php' && is_file($entry))) {
				continue;
			}
			
			$class_name = str_replace('/', '\\', substr($entry, 0, strrpos($entry, '.')));
			if (!class_exists($class_name, true)) {
				continue;
			}
			
			$class =  new ReflectionClass($class_name);
			
			foreach($class->getMethods(256) as $method) {
				if ($method->isConstructor() || $method->isAbstract() || $method->isFinal()) {
					continue;
				}
				
				$method = $method->name;
				$uri = $class_name;
				$url = str_replace('.php', null, basename($entry));
				
				if ($method != 'index') {
					$url .=  DIRECTORY_SEPARATOR . $method;
					$uri .= '->' . $method;
				}
				
				$routes[$url] = $uri;
			}
		}
		
		$this->addRoutes($routes);
	}
	
	/**
	 * Resolves a URI to a class
	 * @param string $uri
	*/
	public function resolve($url) {
		
		$resolve = $this->getRoute($url);
		if ($resolve == false) {
			return false;
		}
		
		if (strstr($resolve, '->')) {
			$action = substr(substr($resolve, strrpos($resolve, '->')), 2);
			$controller = str_replace('->' . $action, null, $resolve);
			$uri = array('module' => $controller, 'action' => $action);
		} else {
			$uri = array('module' => $resolve, 'action' => 'index');
		}
		
		return $uri;		
	}
}