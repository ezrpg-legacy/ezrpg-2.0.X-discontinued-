<?php

namespace ezRPG\Library;

abstract class Module
{
	
	protected $container;
	
    public function __construct(ConfigInterface $container) 
    {
    	$this->container = $container;
    }
}