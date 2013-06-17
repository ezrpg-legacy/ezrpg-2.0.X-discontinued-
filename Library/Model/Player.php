<?php
namespace ezRPG\Library\Model;
use \Exception;

class Player extends \ezRPG\Library\Model
{
	/**
	 * Random state
	 * @var string
	 */
	private $randomState;
	
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

		// prevent service disruptions due to expensive hashing
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

		// fire playerLogin hook
		$pluginData = $this->container['app']->registerHook('playerLogin', $playerMatch);
		
		if (is_array($pluginData)) {
			$playerMatch = $pluginData;
		}
		
		return $playerMatch;
	}
	
	/**
	 * Creates a new player (registration)
	 * @param array $data
	 * @throws Exception
	 * @throws Excption
	 * @return unknown
	 */
	public function create($data)
	{
		if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
			throw new Exception('Invalid email-address');
		}
		
		if (strlen($data['username']) > 25 || strlen($data['username']) < 3) {
			throw new Exception('Username length is invalid');
		}
		
		if (strlen($data['password']) <= 4) {
			throw new Exception('Password length is invalid');
		}
		
		$configPasswordStrength = $this->container['config']['security']['passwordStrength'];
		$password_regex = array(
			0 => '/[a-z]{6,}/',
			1 => '/[a-zA-Z]{8,}/',
			2 => '/[a-zA-Z0-9]{8,}',
			3 => '/[a-zA-Z0-9\!@#\$%\^&\*\(\)-_=+\{\};:,<\.>]/{8,}'
		);
		
		if (preg_match($password_regex[$configPasswordStrength], $data['password']) == false) {
			throw new Exception('Password is too simple');
		}
		
		// all checks succeeded, continue data formatting
		$data['salt'] = $this->createSalt();
		$data['password'] = $this->createHash($data['password'], $data['salt']);
		
		$data['registered'] = date('Y-m-d H:i:s');
		
		// create the actual record
		$data['id'] = parent::create($data);
		
		// fire playerRegistration hook
		$pluginData = $this->container['app']->registerHook('playerRegistration', $data);
		
		if (is_array($pluginData)) {
			$data = $pluginData;
		}
		
		return $data;
	}
	

	public function getOnline()
	{
		$query = $this->query('SELECT * FROM player WHERE lastActive > DATE_SUB(NOW(), INTERVAL 15 MINUTE)');
		return $query->fetchAll(\Pdo::FETCH_ASSOC);
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
}