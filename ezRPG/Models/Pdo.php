<?php

namespace ezRPG\Models;

class Pdo extends \ezRPG\Model
{
	protected
		$handle
		;

	/**
	 * Establish database connection
	 * @param object $app
	 */
	public function __construct(\ezRPG\Interfaces\App $app)
	{
			parent::__construct($app);

		//require('config/pdo.php');

		$config = $this->app->getConfig('db');

		try {
			$this->handle = new \PDO($config['driver'] . ':host=' . $config['host'] . ';port='. $config['port'] . ';dbname=' . $config['database'].';charset=utf8', $config['username'], $config['password']);
		} catch ( \PDOException $e ) {
			throw new \Exception('Error establishing database connection: ' . $e->getMessage());
		}

		$this->handle->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$this->handle->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
	}

	public function getHandle()
	{
		return $this->handle;
	}
}