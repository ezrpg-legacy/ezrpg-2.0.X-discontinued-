<?php

namespace ezRPG\Module\Installer;
use ezRPG\Library\Module;

class Index extends Module
{
	public function index()	{
		$message = "";
		$data['errors'] = 0;
		if(version_compare(PHP_VERSION, '5.3.2') >= 0) {
			$data['php_version'] = true;
		} else {
			$data['php_version'] = false;
			$data['errors'] = 1;
			$message .= "\n<li>You need to be running at least PHP version 5.3.2 for ezRPG to run properly.</li>";
		}

		if ( 1){#is_writable('config.php') ) {
			$data['config_writable'] = true;
		} else {
			$data['config_writable'] = false;
			$data['errors'] = 1;
			if ( !file_exists('config.php') ) {
				if ( 1 ){#rename('config.php.new', 'config.php') ) {
					$data['config_writable'] = true;
				} else {
					$data['config_writable'] = false;
					$data['errors'] = 1;
					$message .= "\n<li>ezRPG needs the configuration file to be writable. Please create a file called 'config.php' in the root directory of ezRPG and make sure it is writable.</li>";
				}
			} else {
				$data['config_writable'] = false;
				$data['errors'] = 1;
				$message .= "\n<li>ezRPG needs the configuration file to be writable. Please create a file called 'config.php' in the root directory of ezRPG and make sure it is writable.</li>";
			}
		}

		if ( 1){#is_writable('settings.php') ) {
			$data['settings_writable'] = true;
		} else {
			$data['settings_writable'] = false;
			$data['errors'] = 1;
			if( !file_exists('settings.php') ) {
				if ( 1 ){#rename('settings.php.new', 'settings.php') ) {
					$data['settings_writable'] = true;
				} else {
					$data['settings_writable'] = false;
					$data['errors'] = 1;
					$message .= "\n<li>ezRPG needs the settings file to be writable. Please create a file called 'settings.php' in the root directory of ezRPG and make sure it is writable.</li>";
				}
			} else {
				$data['settings_writable'] = false;
				$data['errors'] = 1;
				$message .= "\n<li>ezRPG needs the settings file to be writable. Please create a file called 'settings.php' in the root directory of ezRPG and make sure it is writable.</li>";
			}
		}
		
		if ( $data['errors'] == 1 ) {
			$this->container['view']->setMessage("Some errors occoured while checking the requirements.<br /><ul>".$message."</ul>", 'fail');
		}
		$this->container['view']->set('data', $data);
		$this->view->name = "requirements";
	}
}
