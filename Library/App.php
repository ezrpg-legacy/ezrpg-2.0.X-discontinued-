<?php
namespace Library;

class App implements AppInterface
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function run()
    {
    }
}