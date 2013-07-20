<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;
use	\ezRPG\Library\Interfaces\Container;

/**
 * Auth
 * @see Library\Model
 */
class Auth extends Model
{
	const USER_NOT_FOUND	 = 1;
	const PASSWORD_INCORRECT = 2;
	const EMAIL_IN_USE	   = 3;
	const EMAIL_INVALID	  = 4;

	protected $bcryptCost = 10;
	protected $tableName = 'players';

	/**
	 * Perform compatibility check
	 * @param object $app
	 */
	public function __construct(Container $container)
	{
		parent::__construct($container);
		$this->app = $container['app'];

		if ( CRYPT_BLOWFISH != 1 ) {
			throw new \Exception(__CLASS__ . ' requires PHP support for BCrypt');
		}
	}

	/**
	 * Authenticate
	 * @param string $id
	 * @param string $password
	 * @return object
	 */
	public function authenticate($id, $password)
	{
		if ( !filter_var($id, FILTER_VALIDATE_EMAIL) ) {
			$player = $this->find($id, 'username');
		} else {
			$player = $this->find($id, 'email');
		}

		if (empty($player)) {
			throw new \Exception('Player does not exist', self::USER_NOT_FOUND);
		}

		if ( substr(crypt($password, $player->secret_key), 0, 40) != $player->password ) {
			throw new \Exception('Password Incorrect', self::PASSWORD_INCORRECT);
		}

		return $player;
	}

	/**
	 * Register
	 * Create a new player
	 * @param string $email
	 * @param string $password
	 * @return bool
	 */
	public function register($player = '', $email = '', $password = '')
	{
		if ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
			throw new \Exception('Email address invalid', self::EMAIL_INVALID);
		}

		if ( $this->getEmail($email) ) {
			throw new \Exception('Email address already in use', self::EMAIL_IN_USE);
		}
		
		$userName = filter_var($player, FILTER_SANITIZE_STRING);
		
		if ( $this->getPlayer($userName) ) {
			throw new \Exception('Username is already in use', self::EMAIL_IN_USE);
		}
		
		$salt = $this->generateSalt();
		$hash = $this->generateHash($password, $salt);
		$dbh = $this->app->getModel('pdo')->getHandle();
		$config = $this->app->getConfig('db');
		$sth = $dbh->prepare('
			INSERT INTO ' . $config["prefix"] . 'players (
				username,
				email,
				password,
				secret_key,
				registered
			) VALUES (
				:username,
				:email,
				:password,
				:salt,
				' . time() . '
			)
			;');
			
		$sth->bindParam(':username',	$userName);
		$sth->bindParam(':email',	$email);
		$sth->bindParam(':password', $hash);
		$sth->bindParam(':salt', $salt);

		return $sth->execute();
	}

	/**
	 * Update a player's password by ID or email address
	 * @param mixed $id
	 * @param string $password
	 * @return bool
	 */
	public function setPassword($id, $password)
	{
		$salt = $this->generateSalt();
		$hash = $this->generateHash($password, $salt);
		$dbh = $this->app->getModel('pdo')->getHandle();
		$config = $this->app->getConfig('db');
		$sth = $dbh->prepare('
			UPDATE ' . $config["prefix"] . 'players SET
				password = :password,
				secret_key = :salt
			WHERE
				id	= :id OR
				email = :id
			LIMIT 1
			;');

		$sth->bindParam(':id', $id);
		$sth->bindParam(':password', $hash);
		$sth->bindParam(':salt', $salt);

		return $sth->execute();
	}

	/**
	 * getPlayer
	 * Get a player by ID or email address
	 * @param mixed $id
	 */
	public function getPlayer($id)
	{
		$sth = $this->prepare('
			SELECT
				*
			FROM ' . $this->app->config['db']["prefix"] . 'players
			WHERE
				id	= :id OR
				username = :id
			LIMIT 1
			;');

		$sth->bindParam(':id', $id);
		$sth->execute();

		return $sth->fetch(\PDO::FETCH_OBJ);
	}

	/**
	 * getEmail
	 * @param mixed $id
	 */
	public function getEmail($id)
	{
		$sth = $this->prepare('
			SELECT
				id,
				email,
				password,
				secret_key
			FROM ' . $config["prefix"] . 'players
			WHERE
				id	= :id OR
				email = :id
			LIMIT 1
			;');

		$sth->bindParam(':id', $id);
		$sth->execute();

		return $sth->fetch(\PDO::FETCH_OBJ);
	}

	/**
	 * generateHash
	 * @param string $password
	 * @param string $salt
	 * @return string
	 */
	protected function generateHash($password, $salt)
	{
   		return crypt($password, $salt);
	}

	/**
	 * generateSalte
	 * @return string
	 */
	protected function generateSalt()
	{
		$salt = sprintf('$2a$%02d$', $this->bcryptCost) . strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		return $salt;
	}

	/**
	 * setLastLogin
	 * @param mixed $id
	 */
	public function setLastLogin($id)
	{
		$sth = $this->prepare('
			UPDATE ' . $config["prefix"] . 'players SET
				last_login = ' . time() . '
			WHERE
				id	= :id OR
				username = :id
			LIMIT 1
			;');
		
		$sth->bindParam(':id', $id);

		return $sth->execute();
	}

	/**
	 * getLastActive
	 * @return int
	 */
	public function getLastActive()
	{		
		$sth = $this->prepare('
			SELECT COUNT(id) AS count
			FROM ' . $config["prefix"] . 'players
			WHERE 
				last_active > :last
			;');
			
		$time = time() - (60*5);
			
		$sth->bindParam(':last', $time);
		$sth->execute();
		
		$rows = $sth->fetch(\PDO::FETCH_NUM);
		
		return $rows[0];
	}
	
	/**
	 * setLastActive
	 * @param mixed $id
	 */
	public function setLastActive($id)
	{
		$sth = $this->prepare('
			UPDATE ' . $config["prefix"] . 'players SET
				last_active = ' . time() . '
			WHERE
				id	= :id OR
				username = :id
			LIMIT 1
			;');
		
		$sth->bindParam(':id', $id);

		return $sth->execute();
	}
}
