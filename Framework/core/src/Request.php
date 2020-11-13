<?php
namespace Framework\core;

class Request
{
    private $controller;
    private $method;
    private $arguments;
    
    public function __construct() 
    {

        if (isset($_GET['url'])) {
            $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $url = array_filter($url);
            
            $this->controller = ucfirst(strtolower(array_shift($url)));
            $this->method = strtolower(array_shift($url));
            $this->arguments = $url;
        }       
        
        if (!$this->controller) {
            $this->controller = DEFAULT_CONTROLLER;
        }
        
        if (!$this->method) {
            $this->method = 'index';
        }
        
        if (!isset($this->arguments)) {
            $this->arguments = array();
        }
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function getMethod()
    {
        return $this->method;
    }
    
    public function getArgs()
    {
        return $this->arguments;
    }
}