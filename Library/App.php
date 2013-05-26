<?php
namespace Library;

class App implements AppInterface
{
    protected $config;
    
    public function run()
    {
    }
    
    public function setConfig(\Library\ConfigInterface $config)
    {
        $this->config = $config;
    }
    
    public function test()
    {
        return $this->config['database']['username'];
    }
    
}