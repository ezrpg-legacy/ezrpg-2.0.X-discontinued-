<?php

namespace ezRPG\Library;

/**
 * Module
 */
abstract class Module implements Interfaces\Module
{
	protected $app;
	protected $view;
	protected $title;
	protected $container;

	/**
	 * Constructor
	 * @param object $app
	 * @param object $view
	 */
	public function __construct(Interfaces\Container $container)
	{
		$this->container = $container;
		$this->app  = $container['app'];
		$this->view = $container['view'];

		$this->view->set('pageTitle', $this->title);
	}
}