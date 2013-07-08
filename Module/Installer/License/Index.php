<?php

namespace ezRPG\Module\Installer\License;
use ezRPG\Library\Module;

class Index extends Module
{
	public function index()	{
		$license = nl2br(file_get_contents('./Module/Installer/License/license.txt'));
		$this->container['view']->set('license', $license);
		$this->view->name = "license";
	}
}
