<?php

namespace ezRPG\Library;

abstract class Module implements Interfaces\Module
{
	protected
		$app,
		$view,
		$title
		;

	/**
	 * Constructor
	 * @param object $app
	 * @param object $view
	 */
	public function __construct(Interfaces\App $app, Interfaces\View $view)
	{
		$this->app  = $app;
		$this->view = $view;

		$this->view->set('pageTitle', $this->title);
	}
}