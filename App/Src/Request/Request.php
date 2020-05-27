<?php

namespace App\Src\Request;

class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    private $parameters;

    public function __construct(Array $query = [], Array $request = [])
    {
        $this->parameters = array_merge($query, $request);
    }

    public static function createFromGlobals() {
        return new self($_GET, $_POST);
    }

    public function getParameters(String $name) {
        return $this->parameters[$name];
    }

    public function getParameters2() {
        return $this->parameters;
    }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'] ?? self::GET;
    }

    public function getUri() {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return $uri;
    }
}