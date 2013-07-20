<?php

namespace ezRPG\Module\Error404;
use ezRPG\Library\Module;

/**
 * Error404 Index
 * @see Library\Module
 */
class Index extends Module
{
	protected $title = 'Error 404';

	/**
	 * Default action
	 */
	public function index()
	{
		if ( !headers_sent() ) {
			header('HTTP/1.1 404 Not Found');
			header('Status: 404 Not Found');
		}
	}
}