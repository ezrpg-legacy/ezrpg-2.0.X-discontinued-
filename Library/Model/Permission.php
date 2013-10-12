<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;

/**
 * Permission
 * @see Library\Model
 */
class Permission extends Model
{
	protected $tableName = 'permission';
	public function getAll($limit=0, $offset=0) 
	{
		$routes = $this->query('SELECT * FROM <prefix>permission');
		$routes = $routes->fetchAll();
		
		return $routes;
	}

	
}