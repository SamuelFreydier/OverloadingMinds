<?php
namespace App\Src\Route;
class Route 
{
    private $method;
    private $pattern;
    private $callable;
    private $arguments;

    public function __construct(string $method, string $pattern, callable $callable) {
        $this->method = $method;
        $this->pattern = $pattern;
        $this->callable = $callable;
        $this->arguments = array();
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function getPattern() : string
    {
        return $this->pattern;
    }

    public function getCallable() : callable
    {
        return $this->callable;
    }

    public function getArguments() : array
    {
        return $this->arguments;
    }

    public function match(string $method, string $uri) {
        if($this->method != $method) {
            return false;
        }

        if(preg_match($this->compilePattern(), $uri, $this->arguments)) {
            array_shift($this->arguments);
            return true;
        }
        return false;
    }

    private function compilePattern() {
        return sprintf('#^%s$#', $this->pattern);
    }
}