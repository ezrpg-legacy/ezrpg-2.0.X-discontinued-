<?php

namespace ezRPG\Library\Module\Helper;
use \ezRPG\Library\Interfaces\Container;

/**
 * URL
 */
class Url implements ModuleHelperInterface
{
	
	/**
	 * Expose helpers
	 */
	public $helpers = array(
		'redirect'
	);		
	
	public function __construct(Container $container)
	{
		$this->siteConfig =  $container['config']['site'];
	}
	
	/**
	 * Redirects to a URL
	 * @param string $uri
	 * @return string
	 */
	public function redirect($uri)
	{
		header('location: ' . $this->siteConfig['url'] . '/' . $uri);
		return true;
	}
}