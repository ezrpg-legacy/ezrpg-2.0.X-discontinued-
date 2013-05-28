<?php

namespace ezRPG\Library\Router;

class Router implements RouterInterface
{
    protected $routes = array();
    
    public function route()
    {
        
    }
    
    public function addRoutes(array $routes)
    {
        foreach ($routes as $route) {
            $this->addRoute($route);
        }
    }
    
    public function addRoute($route)
    {
        $this->routes[] = $route;
    }
}