<?php

namespace ezRPG\Library\Model;
use \ezRPG\Library\Model;

class RolePermission extends Model
{
	protected $tableName = 'role_permission';
	
	public function getPermissions($role_id) {
		$sql = 'SELECT * FROM <prefix>role_permission rp LEFT JOIN <prefix>permission p ON p.id = rp.permission_id WHERE rp.role_id = :role_id';
		$query = $this->prepare($sql);
		$query->bindParam('role_id', $role_id);
		$query->execute();
		
		return $query->fetchAll();
	}
	
}