<?php

namespace ezRPG\Library;

use ezRPG\Library\AccessControl\Role;
class AccessControl implements Interfaces\AccessControl {
	
	protected $container;
	
	protected $player;
	protected $roles = array();

	/**
	 * Instantiates the ACL
	 * @param Interfaces\Container $container
	 */
	public function __construct(Interfaces\Container &$container) 
	{
		$this->container = $container;
		
		// is the player logged in?
		$sessionModel = $container['app']->getModel('Session');
		$playerModel = $container['app']->getModel('Player');
		
		if ($sessionModel->isLoggedIn()) {
			$this->setPlayer($playerModel->find($sessionModel->getPlayerId()));
		} else {
			$this->setPlayer($playerModel->findGuest());
		}
		
		// lookup in cache for roles
		if ($container['config']['cache']['use'] && isset($container['cache']['acl_player_' . $this->player['id'] .'_roles'])) {
			$roles = $container['cache']['acl_player_' . $this->player['id'] .'_roles'];
		} else {
			$roles = $container['app']->getModel('PlayerRole')->findAllForPlayer($this->player['id']);

			if ($container['config']['cache']['use']) {
				$container['cache']['acl_player_' . $this->player['id'] .'_roles'] = $roles;
			}
		}
		
		$this->addRoles($roles);
		$container['acl'] = $this;
	}
	
	/**
	 * Sets the context of the ACL to a player
	 * @param array $player
	 */
	public function setPlayer($player) 
	{
		$this->player = $player;
	}
	
	/**
	 * Retrieves the context player
	 * @return array
	 */
	public function getPlayer()
	{
		return $this->player;
	}
	
	/**
	 * Retrieves roles player is linked to
	 * @return array
	 */
	public function getRoles()
	{
		return $this->roles;
	}
	
	/**
	 * Adds a role to the current context
	 * @param Role $role
	 */
	public function addRole($role)
	{
		array_push($this->roles, $role);
	}
	
	/**
	 * Adds multiple roles to player's context
	 * @param array $roles
	 */
	public function addRoles($roles)
	{
		foreach($roles as $metadata) {
			$this->addRole(new Role($this->container, $metadata));
		}
	}
	
	/**
	 * Valdiates whether player has permission
	 * 
	 * This is case-insensitive
	 * 
	 * @param string $permission
	 * @return boolean
	 */
	public function verify($permission)
	{
		foreach($this->roles as $role) {
			if ($role->hasPermission($permission) || $role->isRoot()) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Validates whether player has role
	 * 
	 * This is case-insensitive
	 * 
	 * @param string $role
	 * @return boolean
	 */
	public function hasRole($role)
	{
		foreach($this->roles as $role) {
			if (stricmp($role->getTitle(), $role)) {
				return true;
			}
		}
		
		return false;
	}
}