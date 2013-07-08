<?php

namespace ezRPG\Library;

class AccessControl implements Interfaces\AccessControl {
	
	protected $container;
	
	private $_player;
	private $_permissions;
	private $_isRoot;

	protected $rolePermissionModel;
	protected $permissionTypeModel;
	protected $playerRoleModel;
	
	public function __construct(Interfaces\Container &$container) {
		$this->container = $container;
		
		$this->rolePermissionModel = $this->container['app']->getModel('RolePermission');
		$this->permissionTypeModel = $this->container['app']->getModel('PermissionType');
		$this->playerRoleModel = $this->container['app']->getModel('PlayerRole');
		
		// is the player logged in?
		$sessionModel = $this->container['app']->getModel('Session');
		if ($sessionModel->isLoggedIn()) {
			$this->setPlayer($sessionModel->getPlayerId);
		} else {
			$this->setPlayer(0);
		}
		
		$container['acl'] = $this;
	}
	
	/**
	 * Set the context on which the AC opperates to a player
	 * @param unknown $id
	 */
	public function setPlayer($id) {
		$this->_player = $id;
		
		$this->buildPlayerPermissions($id);
	}
	
	/**
	 * Resets a role
	 * @param string $role
	 */
	public function reset($role) {
		$this->_permissions = array();
	}
	
	/**
	 * Validates that a role has a permission
	 * @param string $role
	 * @param string $permission
	 */
	public function validate($permission) {
		if ($this->_player === null || empty($this->_permissions)) {
			return false;
		}
		
		// Root can do anything, period.
		if ($this->_isRoot) {
			return true;
		} 
		
		foreach($this->_permissions as $role) {			
			if (isset($role[$permission])) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Validates that a role has permission for a route
	 * @param string $role
	 * @param string $permission
	 */
	public function validateRoute($route) {
		if ($this->_player === null || empty($this->_permissions)) {
			return false;
		}
		
		// Root can do anything, period.
		if ($this->_isRoot) {
			return true;
		}
		
		$routePermission = $this->permissionTypeModel->find('route', 'title');
		
		$route = strtolower($route);
		foreach($this->_permissions as $role) {
			if (isset($role[$route]) && $role[$route] == $routePermission['id']) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Retrieves list of roles
	 * @param unknown $role
	 */
	public function getRoles($player_id) {
		return $this->playerRoleModel->getRoles($player_id);
	}
	
	/**
	 * Determines whether player has a role
	 * @param string $role
	 * @return boolean
	 */
	public function hasRole($role) {
		if (array_key_exists($role, $this->_permissions)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Retrieves list of permissions for role
	 * @param unknown $role
	 */
	public function getPermissions($role_id) {
		return $this->rolePermissionModel->getPermissions($role_id);
	}
	
	/**
	 * Build permissions for player
	 * @param integer $player_id
	 */
	public function buildPlayerPermissions($player_id) {
		$roles = $this->getRoles($player_id);
		$acl = array();
		
		foreach($roles as $role) {
			if ($role['title'] == 'root') {
				$this->_isRoot = true;
			}
			
			$perms = $this->getPermissions($role['id']);	
			foreach($perms as $perm) {
				$acl[$role['title']][$perm['title']] = $perm['type'];
			}
		}
		
		$this->_permissions = $acl;
	}
}