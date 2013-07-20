<?php

namespace ezRPG\Library;

/**
 * Plugin
 */
abstract class Plugin implements Interfaces\Plugin
{
	protected $app;
	protected $module;
	protected $view;

	/**
	 * Constructor
	 * @param object $app
	 * @param object $view
	 * @param object $module
	 */
	public function __construct(Interfaces\App $app, Interfaces\View $view, Interfaces\Module $module)
	{
		$this->app		= $app;
		$this->view	   = $view;
		$this->module 	  = $module;
	}
}