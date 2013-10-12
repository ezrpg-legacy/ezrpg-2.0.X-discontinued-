<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;

/**
 * Role
 * @see Library\Model
 */
class Role extends Model
{
	protected $tableName = 'role';

	public function getAll($limit=0, $offset=0) 
	{
		$routes = $this->query('SELECT * FROM <prefix>role');
		$routes = $routes->fetchAll();
		
		return $routes;
	}
}