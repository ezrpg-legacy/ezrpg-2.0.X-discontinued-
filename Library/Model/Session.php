<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;

class Session extends Model
{
  protected $messages = array(
		'INFO'		=> '',
		'WARN'		=> '',
		'FAIL'		=> '',
		'GOOD'		=> ''
	);
	
	public function __construct(\ezRPG\Library\Interfaces\App $app)
	{
		parent::__construct($app);
		
		if(!session_id()){
			session_start();
		}
	}

	/**
	 * Set a session value
	 * @param string $variable
	 * @param mixed $value
	 */
	public function set($variable, $value)
	{
		$_SESSION[$variable] = $value;
		if ($variable == "last_active")
		{
			$this->app->getModel('Auth')->setLastActive($this->get('playerid'));
		}
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
	
	function generateSignature() 
	{
		
		$client = array_key_exists('playerid', $_SESSION) ? 
						$_SESSION['playerid'] : 'guest';
		
		if ( $client == 'guest' )
			$key = $this->app->getModel('auth')->generateSalt;
		else
			$key = $this->app->getModel('auth')->getPlayer($client)->secret_key;
			
		$bits = array(
			'playerid'    => $client,
			'ip'        => $_SERVER['REMOTE_ADDR'],
			'browser'   => $_SERVER['HTTP_USER_AGENT'],
			'key'       => $key
		);
			
		$signature = false;

		foreach($bits as $key => $bit) {
			$signature .= $key . $bit; 
		}    
		
		return sha1($signature);
	}

	function compareSignature($origin) {
		return $origin === generateSignature();
	}
	
	public function setMessage($message, $level='info') {
		$level = strtoupper($level);
		
		// for better practices.
		if (array_key_exists($level, $this->messages) === false) {
			throw new \Exception('Message level "' . $level . '" does not exists.');
			return false;
		}
		
		$this->messages[$level] .= $message;
		return true;
	}
	public function validPlayer(){
		if ( !( $playerId = $this->get('playerid') ) ) {
			header('HTTP/1.0 403 Forbidden');

			header('Location: index');

			exit;
		}

		return $playerId;
	}
	
	public function loggedIn(){
		if (  $this->get('playerid')  ) {
			return true;
		}
		return false;
	}
}
