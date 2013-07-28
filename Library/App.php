<?php

namespace ezRPG\Library;

/**
 * App
 * @see http://en.wikipedia.org/wiki/Front_Controller_pattern
 */
class App implements Interfaces\App
{
    protected $container; 
    protected $acl;
    public $plugins = array(); 
    protected $rootPath	= '/';
    protected $view;
    
    /**
     * @param Interfaces\Container $container
     */
    public function __construct(Interfaces\Container $container)
    {
        $container['app'] = $this;
		
		// Skip configuration if INSTALL flag is set
		/*if (defined('INSTALL')) {
			$this->container = $container;
			return;
		}*/

		$config = $container['config'];
		
		// Instantiate the Cache

		if (!defined("INSTALL")) {
			new Cache($container);
			
			if (isset($container['cache']) && isset($container['cache']['config'])) {
				$container['config'] = $container['cache']['config'];
			} elseif (isset($container['cache'])) {
				$container['config']->addDatabaseConfig();
				$container['cache']['config'] = $config;
			}
		}

		// Load plugins
		$this->loadPlugins();

		$this->container = $container;
    }
    
	/**
	 * Run
	 * @return array
	*/
    public function run()
    {
    	// Instantiate ACL, conditionally
    	if ($this->container['config']['security']['acl']['use']) {
			/* @see Library\AccessControl */
    		$this->acl = new AccessControl($this->container);
    	}
    	
		// Instantiate the Router
		$router = new Router($this->container);
		
		$query = isset($_GET['q']) && !empty($_GET['q']) ? strtolower($_GET['q']) : 'index';
		$routeMatch = $router->resolve($query);
		
		if ($routeMatch == false) {
			$routeMatch = $router->resolve('error/file-not-found');
		}

		// Authorization of routes through the AC
		if ($this->container['config']['security']['acl']['use']) {
			if (!empty($routeMatch['access']['permission']) && !$this->acl->verify($routeMatch['access']['permission'])) {
				$routeMatch = $router->resolve('error/access-denied');
			} 
			
			if (!empty($routeMatch['access']['role']) && !$this->acl->hasRole($routeMatch['access']['role'])) {
				$routeMatch = $router->resolve('error/access-denied');
			}
		}
		
		return $this->dispatch($routeMatch);
    }
    
    
    /**
     * Dispatch the request
     * 
     * Instantiates the routed module and assigns
     * sets up the View object.
     * 
     * @param array $routeMatch
     * @return array View and Module
     */
    protected function dispatch(array $routeMatch)
    {
    	// Set up envorinment variables
    	$this->module = 'ezRPG\Module\\' . (!empty($routeMatch['base']) ? str_replace('/', '\\', ucwords($routeMatch['base'])) . '\\' : '') . ucwords($routeMatch['module']) . '\\Index';
    	$this->action = $routeMatch['action'];
    	$this->params = $routeMatch['params'];
    	
    	$this->args = $routeMatch['params'];
    	
    	$moduleName = basename(dirname(str_replace('\\', '/', strtolower($this->module))));
    	
    	/**
    	 * Instantiate the View
    	 * @see Library\View
    	*/
    	$this->view = new View($this->container, $moduleName);
    	
    	// Instantiate the module
    	$this->registerHook('actionBefore');
    	$this->module = new $this->module($this->container);
    	$this->module->{$this->action}($this->params);
    	$this->registerHook('actionAfter');
    	
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
     * Get a model
     * @param string $modelName
     * @return object
     */
    public function getModel($modelName)
    {
    	$modelName = 'ezRPG\Library\Model\\' . ucfirst($modelName);
    
    	/* Instantiate the model */
    	return new $modelName($this->container);
    }
    
    /**
     * Register a hook for plugins to implement
     * @param string $hookName
     * @param array $params
     */
    public function registerHook($hookName, array $params = array())
    {
    	if (!defined('NO_HOOKS')) {
	    	foreach ( $this->plugins as $pluginName => $hooks ) {
	    		if ( in_array($hookName, $hooks) ) {
	    			$plugin = new $pluginName($this->container);
	    
	    			return $plugin->{$hookName}($params);
	    		}
	    	}
    	}
    	return false;
    }
    
    /**
     * Loads plugins
     */
    protected function loadPlugins()
    {
    	if ( $handle = opendir('Library/Plugin') ) {
    		while ( ( $file = readdir($handle) ) !== FALSE ) {
    			if ( is_file('Library/Plugin/' . $file) && preg_match('/^(.+)\.php$/', $file, $match)) {
    				$pluginName = 'ezRPG\Library\Plugin\\' . $match[1];
    	
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
    }
}