<?php

namespace ezRPG\Library\Interfaces;

interface Config extends \ArrayAccess
{
    public function get($key, $default = null);
}