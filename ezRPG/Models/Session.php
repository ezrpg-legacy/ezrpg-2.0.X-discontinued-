<?php

namespace ezRPG\Models;

class Session extends \ezRPG\Model
{
	public function __construct(\ezRPG\Interfaces\App $app)
	{
		parent::__construct($app);

		session_start();
	}

	/**
	 * Set a session value
	 * @param string $variable
	 * @param mixed $value
	 */
	public function set($variable, $value)
	{
		$_SESSION[$variable] = $value;
	}

	/**
	 * Get a session value
	 * @param string $variable
	 * @return mixed
	 */
	public function get($variable)
	{
		if ( isset($_SESSION[$variable]) ) {
			return $_SESSION[$variable];
		}
	}

	/**
	 * Clear all session values
	 */
	public function clear()
	{
		$_SESSION = array();
	}
}