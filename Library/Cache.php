<?php

namespace ezRPG\Library;

/**
 * Cache
 */
class Cache implements Interfaces\Cache
{
	protected $prefix;
	protected $ttl;
	
	/**
	 * Constructor
	 * @param string $prefix
	 * @param int $ttl
	 */
	public function __construct($prefix='ezRPG', $ttl=6400)
	{
		$this->prefix = $prefix;
		$this->ttl = $ttl;
		
		if (!function_exists('apc_fetch')) {
			throw new \Exception('Current configuration does not support APC');
		}
	}
	
	/**
	 * setTtl
	 * @param int $ttl
	 */
	public function setTtl($ttl) {
		$this->ttl = $ttl;
	}
	
	/**
	 * offsetExists
	 * @param string $offset
	 */
	public function offsetExists($offset)
	{
		return apc_exists($this->prefix . $offset);
	}

	/**
	 * offsetGet
	 * @param string $offset
	 */	
	public function offsetGet($offset)
	{
		return apc_fetch($this->prefix . $offset);
	}

	/**
	 * offsetSet
	 * @param string $offset
	 * @param string $value
	 */	
	public function offsetSet($offset, $value)
	{
		apc_store($this->prefix . $offset, $value, $this->ttl);
	}

	/**
	 * offsetUnset
	 * @param string $offset
	 */	
	public function offsetUnset($offset)
	{
		apc_delete($this->prefix . $offset);
	}
}