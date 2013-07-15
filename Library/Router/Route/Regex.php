<?php
namespace ezRPG\Library\Router\Route;

use ezRPG\Library\Router\Route;

class Regex extends Route
{
	
	public function __construct($route, $options) 
	{
		$route = '#' . $route . '#i';
		parent::__construct($route, $options);
	}
	
	/**
	 * Match a given url
	 * 
	 * @param string $url
	 * @return boolean
	 */
	public function match($url)
	{
		return preg_match($this->route, $url) === 1;
	}
	
	/**
	 * Resolve the url
	 * 
	 * @param type $url
	 * @return boolean
	 */
	public function resolve($url) 
	{
		if ($this->match($url) === true) {
			return array_merge($this->options, array('params' => $this->mapParams($url))); 
		}
		
		return false;		
	}
	
	/**
	 * Map the params
	 * 
	 * @param string $url
	 * @return array
	 */
	protected function mapParams($url) 
	{
		$matched = array();
		
		if (empty($this->options['params'])) {
			return $matched;
		}

		preg_match_all($this->route, $url, $matches);
		
		for($i=0;$i<count($matches[1]);$i++) {
			$matched[$this->options['params'][$i]] = ltrim(rawurldecode($matches[1][$i]), '/');
		}
		
		return $matched;
	}
}