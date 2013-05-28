<?php

namespace ezRPG\Library;

class Config implements ConfigInterface
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
        $config = $this->config;
        
        if (is_null($key)) return $config;
        
        foreach (explode('.', $key) as $segment)
        {
            if ( ! is_array($config) or ! array_key_exists($segment, $config))
            {
                return $default;
            }

            $config = $config[$segment];
        }
        
        return $config;
    }
}