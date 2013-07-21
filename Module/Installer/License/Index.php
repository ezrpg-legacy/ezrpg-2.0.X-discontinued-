<?php

namespace ezRPG\Module\Installer\License;
use ezRPG\Library\Module;

/**
 * License Index
 * @see Library\Module
 */
class Index extends Module
{
	/**
	 * Default Action
	 */
	public function index()	{
		$license = nl2br(file_get_contents('./LICENSE.md'));
		$this->container['view']->set('license', $license);
		$this->view->name = "license";
	}
}
