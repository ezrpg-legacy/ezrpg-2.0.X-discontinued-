<?php

namespace ezRPG\Library;

/**
 * Module
 */
abstract class Module implements Interfaces\Module
{
	protected $app;
	protected $view;
	protected $title;
	protected $container;

	/**
	 * Constructor
	 * @param object $app
	 * @param object $view
	 */
	public function __construct(Interfaces\Container $container)
	{
		$this->container = $container;
		$this->app  = $container['app'];
		$this->view = $container['view'];

		$this->view->set('pageTitle', $this->title);
		$this->registerHelpers();
	}
	
	/**
	 * Register helpers
	 */
	protected function registerHelpers()
	{
		foreach(glob(dirname(__FILE__) . '/Module/Helper/*.php') as $file) {
			if (is_dir($file)) {
				continue;
			}
	
			$fileName = basename($file);
			$className = substr($fileName, 0, strrpos($fileName, '.'));
			$className = '\\' . __NAMESPACE__ . '\Module\Helper\\' . $className;
			if (class_exists($className)) {
				$class = new $className($this->container);
				foreach($class->helpers as $helper) {
					$this->helpers[$helper] = $class;
				}
			}
		}
	}
	
	/**
	 * Proxy call requests
	 * @param string $method
	 * @param array $args
	 */
	public function __call($method, $args) {
		if (in_array($method, get_class_methods($this))) {
			return call_user_func_array(array($this, $method), $args);
		}
	
		if (array_key_exists($method, $this->helpers)) {
			$class = $this->helpers[$method];
			return call_user_func_array(array($class, $method), $args);
		}
	}
}