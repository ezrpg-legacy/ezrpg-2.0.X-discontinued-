<?php

namespace ezRPG\Library;

/**
 * Autoloader
 */ 
class Autoloader
{
	private $includePath;
	private $namespace;

    /**
     * @param string $namespace
	 * @param string $includePath
     */
	public function __construct($namespace = null, $includePath = null)
	{
		$this->namespace = $namespace;
		$this->includePath = $includePath;
	}

	/**
	 * loadClass
	 * @param string $className
	 */
	public function loadClass($className)
	{
		$className  = ltrim($className, '\\');
		$fileName   = '';

		if ($lastNsPos = strripos($className, '\\')) {
			$namespace  = substr($className, 0, $lastNsPos);
			$namespace  = str_replace($this->namespace, $this->includePath, $namespace);

			$className  = substr($className, ++$lastNsPos);

			$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
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
		
		if (file_exists($fileName) == false) {
			throw new \Exception('Autoloader could not find "' . $fileName . '"');
		}

		require $fileName;
	}

	/**
	 * register
	 * @param bool $prepend
	 */
	public function register($prepend = false)
	{
		spl_autoload_register(array($this, 'loadClass'), true, $prepend);
	}
}