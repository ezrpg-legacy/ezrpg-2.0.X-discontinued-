<?php

namespace ezRPG\Plugins;

class Example extends \ezRPG\Plugin
{
	/**
	 * Implementation of the actionAfter hook
	 */
	public function actionAfter()
	{
		if ( get_class($this->controller) === 'ezRPG\Controllers\Index' ) {
			$helloWorld = $this->view->get('helloWorld');

			$this->view->set('helloWorld', $helloWorld . ' This string was altered by ' . __CLASS__ . '.');
		}
	}
}
