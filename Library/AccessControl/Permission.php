<?php

namespace ezRPG\Library\AccessControl;

/**
 * Permission
 */
class Permission 
{
	protected $metadata;
	
	/**
	 * Creates a new Permission
	 * @param array $metadata
	 */
	public function __construct($metadata) 
	{
		$this->metadata = $metadata;
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
	 * Retrieves Title
	 * @return string
	 */
	public function getTitle() 
	{
		return $this->metadata['title'];
	}
}