<?php
namespace ezRPG;

class Autoload
{
    public function loadClass($className)
    {
        preg_match('/(^.+\\\)?([^\\\]+)$/', ltrim($className, '\\'), $match);
        $file = str_replace('\\', '/', $match[1]) . str_replace('_', '/', $match[2]) . '.php';
        
        if (is_readable($file)) {
            require $file;
            return true;
        }
    }
}