<?php
namespace Library\Router;

class RouteMatch implements RouteMatchInterface
{
    protected $params = array();
    
    public function __construct(array $params)
    {
        $this->params = $params;
    }
    
    public function getParam($name, $default = null)
    {
        if (array_key_exists($name, $this->params)) {
            return $this->params[$name];
        }

        return $default;
    }
}