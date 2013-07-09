<?php

namespace ezRPG\Library;

/**
 * App
 * @see http://en.wikipedia.org/wiki/Front_Controller_pattern
 */
class App implements Interfaces\App
{
    protected $container, 
    		  $acl,
    		  $action, 
    		  $args 		= array(), 
    		  $module, 
    		  $moduleName, 
    		  $hooks 		= array(),
    		  $plugins 		= array(), 
    		  $rootPath 	= '/',
    		  $view;
    
    //
    protected $params = array();
    
    public function __construct(Interfaces\Container $container)
    {
        $this->container = $container; 
        $this->container['app'] = $this;
		        
        $config = $this->container['config'];
       	if (isset($config['cache']['use']) && $config['cache']['use'] == true) {
        	$this->container['cache'] = new Cache($config['cache']['prefix'], $config['cache']['ttl']);

        	if (!isset($this->container['cache']['config'])) {
        		$this->addDatabaseConfig();
        		$this->container['cache']['config'] = $this->container['config'];
        	} else {
        		$this->container['config'] = $this->container['cache']['config'];
        	}
        } elseif (isset($this->container['config']['db'])) {
        	$this->addDatabaseConfig();
        }
    }
    
    public function run()
    {
    	// instantiate ACL
    	if ($this->container['config']['security']['acl']['use']) {
    		$this->acl = new AccessControl($this->container);
    	}
    	
    	// Load plugins
    	$this->loadPlugins();
		$router = new Router($this->container);
		
		$query = isset($_GET['q']) ? strtolower($_GET['q']) : 'index';
		$routeMatch = $router->resolve($query);

		if ($routeMatch == false) {
			$routeMatch = $router->resolve('error/file-not-found');
		} elseif ($this->container['config']['security']['acl']['use'] 
					&& $this->acl->validateRoute($query) == false) {
			$routeMatch = $router->resolve('error/access-denied');
		}
		
		// Set up envorinment variables
		$this->module = 'ezRPG\Module\\' . (!empty($routeMatch['base']) ? str_replace('/', '\\', ucwords($routeMatch['base'])) . '\\' : '') . ucwords($routeMatch['module']) . '\\Index';
		$this->action = $routeMatch['action'];
		$this->params = $routeMatch['params'];
                
                //We don't want to break the application just
                //yet
                $this->args = $routeMatch['params'];
                
		$moduleName = basename(dirname(str_replace('\\', '/', strtolower($this->module))));
		
		// Instantiate the View
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
     * Get all parameters.
     * 
     * @return array
     */
    
    public function getParams()
    {
        return $this->params;
    }
    
    /**
     * Get a specific parameter.
     * 
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParam($name, $default = null)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return $default;
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
    	return new $modelName($this->container);
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
    
    			return $plugin->{$hookName}($params);
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
    
    /**
     * Load settings from Database
     */
    protected function addDatabaseConfig() {
    	$this->container['config'] = new Config(
    		array_replace_recursive(
    			current((array) $this->container['config']), 
    			$this->getModel('Setting')->getAll()
    		)
    	);
    }
}