<?php
namespace Library;

class Autoloader
{
    private $includePath;
    
    public function __construct($includePath = null)
    {
        $this->includePath = $includePath;
    }
    
    public function loadClass($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        
        require ($this->includePath !== null ? $this->includePath . DIRECTORY_SEPARATOR : '') . $fileName;
    }
    
    public function register($prepend = false)
    {
        spl_autoload_register(array($this, 'loadClass'), true, $prepend);
    }
}