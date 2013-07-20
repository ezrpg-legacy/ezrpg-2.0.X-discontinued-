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
    protected $plugins = array(); 
    protected $rootPath	= '/';
    protected $view;
    
    /**
     * @todo Clean this up
     * @param Interfaces\Container $container
     */
    public function __construct(Interfaces\Container $container)
    {
        $this->container = $container; 
        $this->container['app'] = $this;
		$config = $this->container['config'];
        
		if (file_exists('Module/Installer/locked') || !file_exists('Module/Installer/Index.php')) {
	       	if (isset($config['cache']['use']) && $config['cache']['use'] == true) {
	        	/* @see Library\Cache */
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
    }
    
	/**
	 * Run
	 * @return array
	*/
    public function run()
    {
    	/* Instantiate ACL */
    	if ($this->container['config']['security']['acl']['use']) {
			/* @see Library\AccessControl */
    		$this->acl = new AccessControl($this->container);
    	}
    	
    	/* Load plugins */
    	$this->loadPlugins();
		/* @see Library\Router */
		$router = new Router($this->container);
		
		$query = isset($_GET['q']) ? strtolower($_GET['q']) : 'index';
		$routeMatch = $router->resolve($query);
		
		if ($routeMatch == false) {
			$routeMatch = $router->resolve('error/file-not-found');
		}
		
		if (!empty($routeMatch['access']['permission']) && !$this->acl->verify($routeMatch['access']['permission'])) {
			$routeMatch = $router->resolve('error/access-denied');
		} 
		
		if (!empty($routeMatch['access']['role']) && !$this->acl->hasRole($routeMatch['access']['role'])) {
			$routeMatch = $router->resolve('error/access-denied');
		}
		
		/* Set up envorinment variables */
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
		
		/* Instantiate the module */
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
    	foreach ( $this->plugins as $pluginName => $hooks ) {
    		if ( in_array($hookName, $hooks) ) {
    			$plugin = new $pluginName($this->container);
    
    			return $plugin->{$hookName}($params);
    		}
    	}
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