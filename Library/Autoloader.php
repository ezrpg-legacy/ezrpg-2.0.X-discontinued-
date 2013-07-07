<?php

namespace ezRPG\Library;

class Autoloader
{
	private $includePath;
	private $namespace;

	public function __construct($namespace = null, $includePath = null)
	{
		$this->namespace = $namespace;
		$this->includePath = $includePath;
	}

	public function loadClass($className)
	{
		$className  = ltrim($className, '\\');
		$fileName   = '';

		if ($lastNsPos = strripos($className, '\\')) {
			$namespace  = substr($className, 0, $lastNsPos);
			$namespace  = str_replace($this->namespace, $this->includePath, $namespace);

			$className  = substr($className, ++$lastNsPos);

			$fileName  = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $namespace);
			$fileName .= DIRECTORY_SEPARATOR;
		}
		
		$fileName .= str_replace(
				'\\',
				DIRECTORY_SEPARATOR,
				$className
		);
		
		$fileName .= '.php';

		if (file_exists($fileName) == false) {
			$fileName = $this->includePath . DIRECTORY_SEPARATOR . $fileName;
		}

		require $fileName;
	}

	public function register($prepend = false)
	{
		spl_autoload_register(array($this, 'loadClass'), true, $prepend);
	}
}