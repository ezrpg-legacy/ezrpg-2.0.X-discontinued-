<?php

namespace ezRPG\Library\AccessControl;

/** 
 * Role
 */
class Role 
{
	protected $metadata;
	protected $permissions = array();
	
	/**
	 * Create a new Role
	 * @param object $container
	 * @param array $metadata
	 */
	public function __construct($container, $metadata) 
	{
		$this->metadata = $metadata;
		
		/* Attempt to find in cache */
		if ($container['config']['cache']['use'] &&  isset($container['cache']['acl_roles_' . $metadata['id'] . '_permissions'])) {
			$permissions = $container['cache']['acl_roles_' . $metadata['id'] . '_permissions'];
		} else {
			$permissions = $container['app']->getModel('RolePermission')->getPermissions($metadata['id']);
			
			if ($container['config']['cache']['use']) {
				$container['cache']['acl_roles_' . $metadata['id'] . '_permissions'] = $permission;
			}
		}
		
		/* Inject root override if applicable */
		if (strcasecmp($container['config']['security']['acl']['rootRole'], $metadata['title']) == 0) {
			$this->metadata['isRoot'] = true;
		} else {
			$this->metadata['isRoot'] = false;
		}
		
		foreach($permissions as $metadata) {
			$this->addPermission(new Permission($metadata));
		}
	}
	
	/**
	 * Adds a permission
	 * @param array $permission
	 */
	public function addPermission($permission) 
	{
		array_push($this->permissions, $permission);
	}
	
	/**
	 * Validates if a permission exists within this role
	 * This is case insensitive
	 * @param string $title
	 * @return boolean
	 */
	public function hasPermission($title)
	{
		if ($this->metadata['isRoot']) {
			return true;
		}
		
		foreach($this->permissions as $permission) {
			if (strcasecmp($permission->getTitle(), $title) === 0) {
				/* Type specific logic */
				/*if (!is_null($type)) {
					continue;
				}*/
				
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Retrieves Id
	 * @return integer
	 */
	public function getId()
	{
		return $this->metadata['id'];
	}
	
	/**
	 * Retrieves title
	 * @return string
	 */
	public function getTitle() 
	{
		return $this->metadata['title'];
	}
	
	/**
	 * Find whether the role has root privileges
	 * @return boolean
	 */
	public function isRoot()
	{
		return $this->metadata['isRoot'];
	}
}