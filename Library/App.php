<?php

namespace ezRPG\Library;

/**
 * App
 * @see http://en.wikipedia.org/wiki/Front_Controller_pattern
 */
class App implements Interfaces\App
{
    protected $container, 
    		  $action, 
    		  $args 		= array(), 
    		  $module, 
    		  $moduleName, 
    		  $hooks 		= array(),
    		  $plugins 		= array(), 
    		  $rootPath 	= '/',
    		  $view;
    
    public function __construct(Interfaces\Container $container)
    {
        $this->container = $container; 
        $this->container['app'] = $this;
    }
    
    public function run()
    {
    	// Load plugins
    	$this->loadPlugins();
    	
		// Create router and resolve query
		$router = new Router($this->container);
        $router->addRoute($this->container['config']['router']['routes']);
        $router->buildRoutes('Module');

		$routeMatch = $router->resolve(isset($_GET['q']) ? $_GET['q'] : 'Index');
		if ($routeMatch == false) {
			$routeMatch = $router->resolve('Error404');
		}
		
		// Set up envorinment variables
		$this->module = $routeMatch['module'];
		$this->action = $routeMatch['action'];
		$this->arguments = $routeMatch['arguments'];
		$moduleName = basename(dirname(str_replace('\\', '/', strtolower($this->module))));
		
		// Instantiate the View
		$this->view = new View($this->container, $moduleName);
		
		// Instantiate the module
		$this->registerHook('actionBefore');
		$this->module = new $this->module($this->container);
		$this->module->{$this->action}();
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
     * Verify an argument exists
     * @return bool
     */
    public function getArg($arg)
    {
    	$args = $this->getArgs();
    	if (!empty($args) && $args[0] == $arg) {
    		return true;
    	}
    	return false;
    }
    
    /**
     * Get a model
     * @param string $modelName
     * @return object
     */
    public function getModel($modelName)
    {
    	$modelName = 'ezRPG\Library\Model\\' . ucfirst($modelName);
    
    	// Instantiate the model
    	return new $modelName($this);
    }
    
    /**
     * Deprecated 05/27/13
     * Get a model singleton
     * @param string $modelName
     * @return object
     * @deprecated
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
    			$plugin = new $pluginName($this->container);
    
    			$plugin->{$hookName}($params);
    		}
    	}
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


    public function __get($key)
    {
        return $this->container[$key];
    }
    
    /**
     * Loads plugins
     */
    protected function loadPlugins()
    {
    	if ( $handle = opendir('Library/Plugin') ) {
    		while ( ( $file = readdir($handle) ) !== FALSE ) {
    			if ( is_file('Library/Plugin/' . $file) && preg_match('/^(.+)\.php$/', $file, $match) ) {
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
