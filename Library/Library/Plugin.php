<?php

namespace ezRPG\Library;

abstract class Plugin implements Interfaces\Plugin
{
	protected
		$app,
		$controller,
		$view
		;

	/**
	 * Constructor
	 * @param object $app
	 * @param object $view
	 * @param object $controller
	 */
	public function __construct(Interfaces\App $app, Interfaces\View $view, Interfaces\Module $module)
	{
		$this->app        = $app;
		$this->view       = $view;
		$this->module 	  = $module;
	}
}