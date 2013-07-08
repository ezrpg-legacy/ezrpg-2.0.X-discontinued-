<?php
namespace ezRPG\Library\Router\Route;

use ezRPG\Library\Router\Route;

class Literal extends Route
{   
    /**
     * Match a given url
     * 
     * @param string $url
     * @return boolean
     */
    public function match($url)
    {
    	$url = $this->formatUrl($url);
        return stripos($this->route, $url) !== false;
    }    
    
    /**
     * Resolve the url 
     * 
     * @param string The url to resolve
     * @return mixed
     */
    public function resolve($url) 
    {
        if ($this->match($url) === true) {
            return $this->options;
        }
        
        return false;
    }
    
    /**
     * Format a given url
     * 
     * @param string
     * @return string The formatted url
     */
    protected function formatUrl($url) 
    {
        $url = rtrim($url, '/');
    	return $url;
    }
}