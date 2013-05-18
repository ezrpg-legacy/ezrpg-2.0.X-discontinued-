<?php

namespace ezRPG\Modules;

class Error404 extends \ezRPG\Module
{
	protected
		$title = 'Error 404'
		;

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

	/**
	 * Not implemented action
	 */
	public function notImplemented()
	{
		$this->index();
	}
}