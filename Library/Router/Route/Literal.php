<?php

namespace ezRPG\Library\Router\Route;
use ezRPG\Library\Router\Route;

class Literal extends Route {
    
    public function match($url)
    {
    	$url = $this->formatUrl($url);
        return stripos($this->route, $url) !== false;
    }    
    
    public function resolve($url) 
    {
        if ($this->match($url) === true) {
            return $this->options;
        }
        
        return false;
    }
    
    protected function formatUrl($url) 
    {
    	$url = rtrim($url, '/');
    	return $url;
    }

}