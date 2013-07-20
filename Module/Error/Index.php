<?php

namespace ezRPG\Module\Error;
use ezRPG\Library\Module;

/**
 * Error Index
 * @see Library\Module
 */
class Index extends Module
{
	protected $title = 'Error 404';

	/**
	 * Default action
	 * @param array $params
	 */
	public function index($params=array())
	{
		switch ($params['type']) {
			case 'access-denied' :
					if ( !headers_sent() ) {
						header('HTTP/1.1 403 Forbidden');
						header('Status: 403 Forbidden');
					}
				
					$this->container['view']->name = 'error/access_denied';
					break;
			case 'file-not-found' :
				/* falls through */
			default :
					if ( !headers_sent() ) {
						header('HTTP/1.1 404 Not Found');
						header('Status: 404 Not Found');
					}
				
					$this->container['view']->name = 'error/file_not_found';
					break;
		}
	}
}