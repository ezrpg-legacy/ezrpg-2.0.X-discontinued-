<?php
namespace ezRPG\Library;

class View implements Interfaces\View
{
	protected $container;
	protected $variables = array();
	protected $helpers;

	public $layout;
	public $name;

	/**
	 * Constructor
	 * @param object $app
	 * @param string $name
	 */
	public function __construct(Interfaces\Container $container, $name)
	{
		$this->container = $container;
		$this->name = $name;
		$this->layout = $container['config']['site']['theme'];
		$container['view'] = $this;
		
		$this->registerHelpers();
		
	}

	/**
	 * Get a view variable
	 * @params string $variable
	 * @params bool $htmlEncode
	 * @return mixed
	 */
	public function get($variable, $htmlEncode = false)
	{
		if ( isset($this->variables[$variable]) ) {
			if ( $htmlEncode ) {
				return $this->htmlEncode($this->variables[$variable]);
			} else {
				return $this->variables[$variable];
			}
		}
	}

	/**
	 * Set a view variable
	 * @param string $variable
	 * @param mixed $value
	 */
	public function set($variable, $value)
	{
		$this->variables[$variable] = $value;
	}

	/**
	 * Render the view
	 */
	public function render()
	{
		$file = 'Theme/' . $this->layout . '/' . $this->name . '.phtml';
		if ( is_file($file) ) {
			header('X-Generator: ezRPG');
			require $file;
		} else {
			//throw new \Exception('View not found'); // Not sure why a view would need to be mandatory.
		}
	}
	
	/**
	 * Register helpers
	 */
	protected function registerHelpers()
	{
		foreach(scandir(dirname(__FILE__) . '/View/Helper') as $file) {
			if (is_dir($file)) {
				continue;
			}
				
			$fileName = basename($file);
			$className = substr($fileName, 0, strrpos($fileName, '.'));
			$className = '\\' . __NAMESPACE__ . '\View\Helper\\' . $className;
			if (class_exists($className)) {
				$class = new $className($this->container);
				foreach($class->helpers as $helper) {
					$this->helpers[$helper] = $class;
				}
			}
		}
	}
	
	/**
	 * Proxy call requests
	 * @param string $method
	 * @param array $args
	 */
	public function __call($method, $args) {
		if (in_array($method, get_class_methods($this))) {
			return call_user_func_array(array($this, $method), $args);
		}
		
		if (array_key_exists($method, $this->helpers)) {
			$class = $this->helpers[$method];
			return call_user_func_array(array($class, $method), $args);
		}
	} 
}