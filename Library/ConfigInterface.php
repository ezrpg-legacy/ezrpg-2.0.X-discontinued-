<?php
namespace Library;

interface ConfigInterface
{
    public function get($key, $default = null);
}