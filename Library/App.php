<?php

namespace ezRPG\Library;

use \RuntimeException,
	ezRPG\Library\Router\Router;

class App implements AppInterface
{
    protected $container;
    protected $module;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container; 
    }
    
    public function run()
    {
        $router = new Router;
        
        
        $routeMatch = $router->route();
        
        if ($routeMatch === null) {
            
        } else {
            
            $module = '\\ezRPG\\Module\\' . $routeMatch->getParam('module');
            
            $this->module = new $module;
 
            
            $action = $routeMatch->getParam('index');
            
        }
        
    }
}
