<?php
namespace Library\Router;

class Router implements RouterInterface
{
    protected $routes = array();
    
    public function route()
    { 
        $params = array(
            'module' => 'Home\Home',
            'action' => 'index',
        );
        
        return new RouteMatch($params);
    }
}