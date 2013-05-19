<?php

namespace ezRPG;

class App implements Interfaces\App
{
	protected
		$action,
		$args           = array(),
		$config         = array(),
		$module,
		$moduleName,
		$hooks          = array(),
		$plugins        = array(),
		$rootPath       = '/',
		$singletons     = array(),
		$view
		;

	/**
	 * Run the application
	 */
	public function run()
	{
		// Determine the client-side path to root
		if ( !empty($_SERVER['REQUEST_URI']) ) {
			$this->rootPath = preg_replace('/(index\.php)?(\?.*)?$/', '', rawurldecode($_SERVER['REQUEST_URI']));
		}

		// Run from command line, e.g. "php index.php -q index"
		$opt = getopt('q:');

		if ( isset($opt['q']) ) {
			$_GET['q'] = $opt['q'];
		}

		if ( !empty($_GET['q']) ) {
			$this->rootPath = preg_replace('/' . preg_quote($_GET['q'], '/') . '$/', '', $this->rootPath);
		}

		if (!(array_key_exists('q', $_GET) && !empty($_GET['q']))) {
			$_GET['q'] = 'Index';
		}

		// Create router and resolve query
		$router = new Router;
		$router->buildRoutes('ezRPG/Modules');
		
		$router->addRoute(array('Error404' => 'ezRPG\Modules\Error404'));
		$routeMatch = $router->resolve($_GET['q']);
		
		if ($routeMatch == false) {
			$routeMatch = $router->resolve('Error404');
		}
		
		$this->module = $routeMatch['module'];
		$this->action = $routeMatch['action'];
		
		// Instantiate the view
		$this->moduleName = strtolower(substr($this->module, strrpos($this->module, '\\') + 1));
		$this->view = new View($this, $this->moduleName);

		// Instantiate the controller
		$this->module = new $this->module($this, $this->view);

		// Load plugins
		if ( $handle = opendir('ezRPG/Plugins') ) {
			while ( ( $file = readdir($handle) ) !== FALSE ) {
				if ( is_file('ezRPG/Plugins/' . $file) && preg_match('/^(.+)\.php$/', $file, $match) ) {
					$pluginName = 'ezRPG\Plugins\\' . $match[1];

					$this->plugins[$pluginName] = array();

					foreach ( get_class_methods($pluginName) as $methodName ) {
						$method = new \ReflectionMethod($pluginName, $methodName);

						if ( $method->isPublic() && !$method->isFinal() && !$method->isConstructor() ) {
							$this->plugins[$pluginName][] = $methodName;
						}
					}
				}
			}

			ksort($this->plugins);

			closedir($handle);
		}

		// Removed: Call the controller action (Not Utilized, Swiftlet Example of Hook Usage)
		//$this->registerHook('actionBefore');
		
		$this->module->{$this->action}();
		
		// Removed: Call the controller action (Not Utilized, Swiftlet Example of Hook Usage)
		//$this->registerHook('actionAfter');

		return array($this->view, $this->module);
	}

	/**
	 * Serve the page
	 */
	public function serve()
	{
		$this->view->render();
	}

	/**
	 * Get a configuration value
	 * @param string $variable
	 * @return mixed
	 */
	public function getConfig($variable)
	{
		if ( isset($this->config[$variable]) ) {
			return $this->config[$variable];
		}
	}

	/**
	 * Set a configuration value
	 * @param string $variable
	 * @param mixed
	 */
	public function setConfig($variable, $value)
	{
		$this->config[$variable] = $value;
	}

	/**
	 * Get the client-side path to root
	 * @return string
	 */
	public function getRootPath()
	{
		return $this->rootPath;
	}

	/**
	 * Get the module name
	 * @return string
	 */
	public function getModuleName()
	{
		return $this->moduleName;
	}

	/**
	 * Get the action name
	 * @return string
	 */
	public function getAction()
	{
		return $this->action;
	}

	/**
	 * Get the arguments
	 * @return array
	 */
	public function getArgs()
	{
		return $this->args;
	}

	/**
	 * Get a model
	 * @param string $modelName
	 * @return object
	 */
	public function getModel($modelName)
	{
		$modelName = 'ezRPG\Models\\' . ucfirst($modelName);

		// Instantiate the model
		return new $modelName($this);
	}

	/**
	 * Get a model singleton
	 * @param string $modelName
	 * @return object
	 */
	public function getSingleton($modelName)
	{
		if ( isset($this->singletons[$modelName]) ) {
			return $this->singletons[$modelName];
		}

		$model = $this->getModel($modelName);

		$this->singletons[$modelName] = $model;

		return $model;
	}

	/**
	 * Register a hook for plugins to implement
	 * @param string $hookName
	 * @param array $params
	 */
	public function registerHook($hookName, array $params = array())
	{
		$this->hooks[] = $hookName;

		foreach ( $this->plugins as $pluginName => $hooks ) {
			if ( in_array($hookName, $hooks) ) {
				$plugin = new $pluginName($this, $this->view, $this->module);

				$plugin->{$hookName}($params);
			}
		}
	}

	/**
	 * Class autoloader
	 * @param $className
	 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
	 */
	public function autoload($className)
	{
		preg_match('/(^.+\\\)?([^\\\]+)$/', ltrim($className, '\\'), $match);
		$file = str_replace('\\', '/', $match[1]) . str_replace('_', '/', $match[2]) . '.php';

		require $file;
	}

	/**
	 * Error handler
	 * @param int $number
	 * @param string $string
	 * @param string $file
	 * @param int $line
	 */
	public function error($number, $string, $file, $line)
	{
		throw new \Exception('Error #' . $number . ': ' . $string . ' in ' . $file . ' on line ' . $line);
	}
}
