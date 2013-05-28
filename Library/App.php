<?php

namespace ezRPG\Library;

use \RuntimeException,
	ezRPG\Library\Router\Router;

use Library\Router\Router;

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
<<<<<<< HEAD
        $router = new Router;
=======
        $router = new Router();
>>>>>>> 4b834a18650eae41cddb6f27bf0182d9e5bdcbd9
        
        
        $routeMatch = $router->route();
        
        if ($routeMatch === null) {
            
        } else {
            
<<<<<<< HEAD
            $module = '\\ezRPG\\Module\\' . $routeMatch->getParam('module');
            
            $this->module = new $module;
=======
            $module = 'Module\\' . $routeMatch->getParam('module');
            
            $this->module = new $module();
>>>>>>> 4b834a18650eae41cddb6f27bf0182d9e5bdcbd9
 
            
            $action = $routeMatch->getParam('index');
            
        }
<<<<<<< HEAD
=======
        
>>>>>>> 4b834a18650eae41cddb6f27bf0182d9e5bdcbd9
        
    }
}
