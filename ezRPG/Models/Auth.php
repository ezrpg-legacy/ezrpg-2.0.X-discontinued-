<?php

namespace ezRPG\Models;

class Auth extends \ezRPG\Model
{
	const
		USER_NOT_FOUND     = 1,
		PASSWORD_INCORRECT = 2,
		EMAIL_IN_USE       = 3,
		EMAIL_INVALID      = 4
		;

	protected
		$bcryptCost = 10
		;

	/**
	 * Perform compatibility check
	 *
	 * @param object $app
	 */
	public function __construct(\ezRPG\Interfaces\App $app)
	{
		parent::__construct($app);

		if ( CRYPT_BLOWFISH != 1 ) {
			throw new \Exception(__CLASS__ . ' requires PHP support for BCrypt');
		}
	}

	/**
	 * Authenticate
	 *
	 * @param string $email
	 * @param string $password
	 * @return object
	 */
	public function authenticate($id, $password)
	{
		if ( !filter_var($id, FILTER_VALIDATE_EMAIL) ) {
			$player = $this->getPlayer($id);
		} else {
			$player = $this->getEmail($id);
		}


		if ( !$player ) {
			throw new \Exception('Player does not exist', self::USER_NOT_FOUND);
		}

		if ( substr(crypt($password, $player->secret_key), 0, 40) != $player->password ) {
			throw new \Exception('Password Incorrect', self::PASSWORD_INCORRECT);
		}

		return $player;
	}

	/**
	 * Create a new player
	 *
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
		
		$dbh = $this->app->getSingleton('pdo')->getHandle();
		
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
		$sth->bindParam(':username',    $userName);
		$sth->bindParam(':email',    $email);
		$sth->bindParam(':password', $hash);
		$sth->bindParam(':salt', $salt);

		return $sth->execute();
	}

	/**
	 * Update a player's password by ID or email address
	 *
	 * @param mixed $id
	 * @param string $password
	 * @return bool
	 */
	public function setPassword($id, $password)
	{
		$salt = $this->generateSalt();

		$hash = $this->generateHash($password, $salt);

		$dbh = $this->app->getSingleton('pdo')->getHandle();
		
		$config = $this->app->getConfig('db');
		
		$sth = $dbh->prepare('
			UPDATE ' . $config["prefix"] . 'players SET
				password = :password,
				secret_key = :salt
			WHERE
				id    = :id OR
				email = :id
			LIMIT 1
			;');

		$sth->bindParam(':id', $id);
		$sth->bindParam(':password', $hash);
		$sth->bindParam(':salt', $salt);

		return $sth->execute();
	}

	/**
	 * Get a player by ID or email address
	 *
	 * @param mixed $id
	 */
	public function getPlayer($id)
	{
		$dbh = $this->app->getSingleton('pdo')->getHandle();
		
		$config = $this->app->getConfig('db');
		
		$sth = $dbh->prepare('
			SELECT
				*
			FROM ' . $config["prefix"] . 'players
			WHERE
				id    = :id OR
				username = :id
			LIMIT 1
			;');

		$sth->bindParam(':id', $id);

		$sth->execute();

		return $sth->fetch(\PDO::FETCH_OBJ);
	}
	
	public function getEmail($id)
	{
		$dbh = $this->app->getSingleton('pdo')->getHandle();
		
		$config = $this->app->getConfig('db');
		
		$sth = $dbh->prepare('
			SELECT
				id,
				email,
				password,
				secret_key
			FROM ' . $config["prefix"] . 'players
			WHERE
				id    = :id OR
				email = :id
			LIMIT 1
			;');

		$sth->bindParam(':id', $id);

		$sth->execute();

		return $sth->fetch(\PDO::FETCH_OBJ);
	}
	
	protected function generateHash($password, $salt)
	{

    return crypt($password, $salt);
	}
	
	protected function generateSalt()
	{
	$salt = sprintf('$2a$%02d$', $this->bcryptCost) . strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
	return $salt;
	}
 
	public function setLastLogin($id)
	{
		$dbh = $this->app->getSingleton('pdo')->getHandle();

		$config = $this->app->getConfig('db');
		
		$sth = $dbh->prepare('
			UPDATE ' . $config["prefix"] . 'players SET
				last_login = ' . time() . '
			WHERE
				id    = :id OR
				username = :id
			LIMIT 1
			;');
		$sth->bindParam(':id', $id);

		return $sth->execute();
	}
}