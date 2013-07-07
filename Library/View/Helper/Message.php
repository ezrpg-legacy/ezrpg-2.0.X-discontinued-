<?php

namespace ezRPG\Library\View\Helper;
use \ezRPG\Library\Interfaces\Container;

class Message implements ViewHelperInterface {

	/**
	 * Expose helpers
	 */
	public $helpers = array(
			'setMessage',
	);
	
	private $view;
	

	public function __construct(Container $container) {
		$this->view =  $container['view'];
	}

	/**
	 * Create a msg var.
	 * $this->get('msg', FALSE) FALSE must be used to decode the HTML
	 */
	public function setMessage($message, $warn)
	{
		$html = '<span class="msg '.$warn.'">'.$message.'</span>';
		$this->view->set('msg', $html);
	}
}