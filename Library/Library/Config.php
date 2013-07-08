<?php

namespace ezRPG\Library;
use \Exception;

class Config implements Interfaces\Config
{
    private $config = array();

    public function __construct(array $config = array())
    {
        $this->config = $config;
    }
    
    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->config[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->config[] = $value;
        } else {
            $this->config[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->config[$offset]);
    }
    
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->config)) {
        	return $this->config[$key];
        }
        
        throw new Exception('Key "' . $key . '" not found within config');
    }
}