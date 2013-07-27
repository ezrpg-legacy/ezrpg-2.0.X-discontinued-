<?php

namespace ezRPG\Library\Model;
use \Exception;
use	\InvalidArgumentException;
use ezRPG\Library\Model;

/**
 * Player
 * @see Library\Model
 */
class Player extends Model
{
	/**
	 * Random state
	 * @var string
	 */
	private $randomState;
	
	protected $useCaching = false;
	
	/**
	 * Validates a username/email and passwords
	 * @param string $player
	 * @param string $password
	 * @throws Exception
	 */
	public function authenticate($player, $password)
	{
		if (filter_var($player, FILTER_VALIDATE_EMAIL)) {
			$playerMatch = $this->find($player, 'email');
		} else {
			$playerMatch = $this->find($player, 'username');
		}

		/* Prevent service disruptions due to expensive hashing */
		$rounds = $this->container['config']['security']['hashRounds'];
		if ($playerMatch == false) {
			sleep($rounds / 10);
			throw new Exception('Invalid username or email provided');
		}
		
		$passwordTest = $this->validatePassword($password, $playerMatch['password']);
		if ($passwordTest == false) {
			sleep($rounds / 10);
			throw new Exception('Invalid password');
		}

		/* Fire playerLogin hook */
		$pluginData = $this->container['app']->registerHook('playerLogin', $playerMatch);
		
		if (is_array($pluginData)) {
			$playerMatch = $pluginData;
		}
		
		return $playerMatch;
	}
	
	/**
	 * create
	 * Creates a new player (registration)
	 * Exception codes
	 * 1 - Invalid email
	 * 2 - Invalid username
	 * 4 - Username in use
	 * 8 - Email in use
	 * 16 - Invalid confirm password
	 * 32 - Invalid password
	 * 
	 * @param array $data
	 * @throws Exception
	 * @throws InvalidArgumentException
	 * @return unknown
	 */
	public function create($data)
	{
		/* It's good for UI to produce a list of invalid input */
		$errors = array();
		
		/* Validate email address against ??(unknown) specification */
		if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			$errors[] = new InvalidArgumentException('An invalid email address was provided', 1);
		} 
		
		/* Validate username for length */
		if (strlen($data['username']) > 25 || strlen($data['username']) < 3) {
			$errors[] = new InvalidArgumentException('Username length is invalid, it should be more than three characters and less than twenty-five', 2);
		} 
		
		/* Check for the possible existence of accounts with same username */
		if ($this->find($data['username'], 'username')) {
			$errors[] = new InvalidArgumentException('Another account is already registered with the same username', 4);
		} 
		
		/* Check for an existent account with same email-address */
		if($this->find($data['email'], 'email')) {
			$errors[] = new InvalidArgumentException('Email address is already associated with another account', 8);
		}

		/* Check that confirmation password is the same as password */
		if ($data['confirm_password'] !== $data['password']) {
			$errors[] = new InvalidArgumentException('Confirmation password does not match original', 16);
		}
		
		unset($data['confirm_password']);
		 
		/* Check password validity against predefined algorithm */
		$configPasswordStrength = $this->container['config']['security']['passwordStrength'];
		$password_regex = array(
			0 => '/[a-z]{6,}/',
			1 => '/[a-zA-Z]{8,}/',
			2 => '/[a-zA-Z0-9]{8,}',
			3 => '/[a-zA-Z0-9\!@#\$%\^&\*\(\)-_=+\{\};:,<\.>]/{8,}'
		);
		
		if (preg_match($password_regex[$configPasswordStrength], $data['password']) === false || empty($data['password'])) {
			$password_message = 'Password is too simple, ';
			
			/* Generate a nice message */
			switch ($configPasswordStrength) {
				case 0 : 
					$password_message .= 'should contain alphabetic characters and be six characters or longer';
					break;
				case 1 :
					$password_message .= 'should contain capitalized characters and be eight characters or longer';
					break;
				case 2 :
					$password_message .= 'should contain capitalized characters, numbers and be eight characters or longer';
					break;
				case 3 :
					$password_message .= 'should contain capitalized characters, numbers, symbols and be eight characters or longer';
					break;
			}
			
			$errors[] = new InvalidArgumentException($password_message, 32);
		}
		
		/* If there were any errors, quit early */
		if (count($errors) <> 0) {
			throw new Exception(serialize($errors), null,  end($errors));
		} 
		
		/* All checks succeeded, continue data formatting */
		$data['salt'] = $this->createSalt();
		$data['password'] = $this->createHash($data['password'], $data['salt']);
		$data['title'] = ucfirst($data['username']);
		$data['registered'] = date('Y-m-d H:i:s');
		$data['lastActive'] = $data['registered'];
		$data['active'] = $this->container['app']->registerHook('playerActivation', $data);

		print_r($this->container['app']->plugins);
		/* Create the actual record */
		$data['id'] = parent::add($data);
		
		/* Fire playerRegistration hook */
		$pluginData = $this->container['app']->registerHook('playerRegistration', $data);
		
		if (is_array($pluginData)) {
			$data = $pluginData;
		}
		
		return $data;
	}
	
	/**
	 * getOnline
	 * @return object
	 */
	public function getOnline()
	{
		$query = $this->query('SELECT * FROM player WHERE lastActive > DATE_SUB(NOW(), INTERVAL 15 MINUTE)');
		return $query->fetchAll(\Pdo::FETCH_ASSOC);
	}
	
	/**
	 * getNumOnline
	 * @return int
	 */
	public function getNumOnline()
	{
		$query = $this->query('SELECT * FROM player WHERE lastActive > DATE_SUB(NOW(), INTERVAL 15 MINUTE)');
		return $query->rowCount();
	}
	
	/**
	 * Creates a new hash
	 * @param string $input
	 * @param string $salt
	 * @return mixed
	 */
	public function createHash($input, $salt) 
	{
		$hash = crypt($input, $salt);
		
		if (strlen($hash) > 13) {
			return $hash;
		}
		
		return false;
	}

	/**
	 * Compare two passwords
	 * @param string $input
	 * @param string $existent
	 * @return boolean
	 */
	public function validatePassword($input, $existent)
	{
		$hash = crypt($input, $existent);
		return $hash === $existent;
	}

	/**
	 * Create a bcrypt salt
	 * @return string
	 */
	public function createSalt() 
	{
		$rounds = $this->container['config']['security']['hashRounds'];
		$saltSize = $this->container['config']['security']['hashSaltSize'];
		
		$salt = sprintf('$2a$%02d$', $rounds);
		$salt .= $this->encodeBytes($this->getRandomBytes($saltSize));

		return $salt;
	}

	/**
	 * Retrieves some randomly generated data
	 * @param string $size
	 */
	private function getRandomBytes($size) 
	{
		$bytes = '';

		if($this->randomState === null) {
			$this->randomState = microtime();
			if(function_exists('getmypid')) {
				$this->randomState .= getmypid();
			}
		}

		for($i = 0; $i < $size; $i += 16) {
			$this->randomState = md5(microtime() . $this->randomState);
			$bytes .= md5($this->randomState, true);
		}

		return substr($bytes, 0, $size);
	}

	/**
	 * Encode bytes
	 * @see PHP Password Hashing Framework
	 * @param mixed $input
	 * @return string
	 */
	private function encodeBytes($input) 
	{
		$itoa64 = './ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

		$output = '';
		$i = 0;
		do {
			$c1 = ord($input[$i++]);
			$output .= $itoa64[$c1 >> 2];
			$c1 = ($c1 & 0x03) << 4;
			if ($i >= 16) {
				$output .= $itoa64[$c1];
				break;
			}

			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 4;
			$output .= $itoa64[$c1];
			$c1 = ($c2 & 0x0f) << 2;

			$c2 = ord($input[$i++]);
			$c1 |= $c2 >> 6;
			$output .= $itoa64[$c1];
			$output .= $itoa64[$c2 & 0x3f];
		} while (1);

		return $output;
	}

	/**
	 * Retrieve a guest player listing
	 * @return array
	 */
	public function findGuest() {
		return array(
			'id' => 0,
			'name' => 'guest'
		);
	}
}