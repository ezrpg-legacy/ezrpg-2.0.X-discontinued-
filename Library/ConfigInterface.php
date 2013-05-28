<?php

namespace ezRPG\Library;

interface ConfigInterface extends \ArrayAccess
{
    public function get($key, $default = null);
}