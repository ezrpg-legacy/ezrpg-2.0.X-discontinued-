<?php

namespace ezRPG\Library;

use \RuntimeException;

class App implements AppInterface
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container; 
    }
    
    public function run()
    {
    	// does nothing.
    	throw new RuntimeException('Not implemeneted');
    }
}