<?php

namespace ezRPG\Library;

class Cache implements Interfaces\Cache
{
	protected $prefix;
	protected $ttl;
	
	public function __construct($prefix='ezRPG', $ttl=6400)
	{
		$this->prefix = $prefix;
		$this->ttl = $ttl;
		
		if (!function_exists('apc_fetch')) {
			throw new \Exception('Current configuration does not support APC');
		}
	}
	
	public function setTtl($ttl) {
		$this->ttl = $ttl;
	}
	
	public function offsetExists($offset)
	{
		return apc_exists($this->prefix . $offset);
	}
	
	public function offsetGet($offset)
	{
		return apc_fetch($this->prefix . $offset);
	}
	
	public function offsetSet($offset, $value)
	{
		apc_store($this->prefix . $offset, $value, $this->ttl);
	}
	
	public function offsetUnset($offset)
	{
		apc_delete($this->prefix . $offset);
	}
}