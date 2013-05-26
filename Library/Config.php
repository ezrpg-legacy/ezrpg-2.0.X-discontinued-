<?php
namespace Library;

class Config implements ConfigInterface, \ArrayAccess
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
}