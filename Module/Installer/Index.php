<?php

namespace ezRPG\Module\Installer;
use ezRPG\Library\Module;

/**
 * Installer Index
 * @see Library\Module
 */
class Index extends Module
{
	/**
	 * Default Action
	 */
	public function index()
	{
		$checks = array(
			'PHP Version' => array(
				'passed' => false,
				'required' => true,
				'message' => 'ezRPG uses some of the newer libraries only available in versions of PHP 5.3.2. and later, while we detected that you are currently running on PHP ' . PHP_VERSION . '.<br />' .
							 'If you are using your own infrastructure, please upgrade PHP to the latest version.<br />' .
							 'If you are on a shared hosting provider this may be some troublesome news, but ask them if they might be able to help you with this problem.'
			),
				
			'APC Support' => array(
				'passed' => false,
				'required' => false,
				'message' => 'ezRPG uses APC for caching expensive computations to improve performance.<br />' .
							 'Although this is not a scrict requirement, we highly suggest that you(or your hosting provider) enable this functionality.'
			),
				
			'Root directory writable' => array(
				'passed' => false,
				'required' => true,
				'message' => 'ezRPG writes configuration files within the root directory of the application.<br />' .
							 'You can resolve this problem by changing the permission(even temporarily) for the directory <code>' . getcwd() . '</code>.'
			)					
		);
		
		// A series of checks to confirm compatibility
		if (version_compare(PHP_VERSION, '5.3.2', '>=')) {
			$checks['PHP Version']['passed'] = true;
		}
		
		if (function_exists('apc_fetch')) {
			$checks['APC Support']['passed'] = true;
		}
		
		if (is_writable(getcwd())) {
			$checks['Root directory writable']['passed'] = true;
		}
		
		$this->view->set('checks', $checks);
		$this->view->name = 'requirements';
	}
}