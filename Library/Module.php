<?php

namespace ezRPG\Library;

abstract class Module implements Interfaces\Module
{
	protected
		$app,
		$view,
		$title,
		$container
		;

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