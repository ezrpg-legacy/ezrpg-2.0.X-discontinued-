<?php

namespace ezRPG\Library\Interfaces;

/**
 * Interface for Router class
 * 
 * Defines the structure of the Router class.
 */
interface Router 
{
	/**
	 * Constructor
	 * @param Container $container
	 */
	public function __construct(Container $container);
	
	/**
	 * Inject multiple routes
	 * @param array $routes
	 */
	public function addRoutes(array $routes);

	/**
	 * Retrieve multiple routes
	 */
	public function getRoutes();

	/**
	 * Inject a single route
	 * @param array $route
	 */
	public function addRoute($route);

	/**
	 * Retrieves a single route by URI
	 * @param string $path
	 */
	public function getRoute($route);

	/**
	 * Automatically add routes by directory
	 * @param string $directory
	 */
	public function buildRoutes($directory);

	/**
	 * Resolves a URI to a class
	 * @param string $uri
	 */
	public function resolve($uri);
}