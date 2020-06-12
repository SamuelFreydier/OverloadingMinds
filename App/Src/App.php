<?php

namespace App\Src;
use App\Src\Route\Route;
use App\Src\ServiceContainer\ServiceContainer;
use App\Src\Request\Request;
use App\Src\Response\Response;

class App
{
    const GET = "GET";
    const POST = "POST";
    const PUT = "PUT";
    const DELETE = "DELETE";

    private $routes = array();
    private $statusCode;

    private $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer) 
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function getService(string $serviceName) {
        return $this->serviceContainer->get($serviceName);
    }

    public function setService(string $serviceName, $assigned) {
        $this->serviceContainer->set($serviceName, $assigned);
    }

    public function unsetService(string $serviceName) {
        $this->serviceContainer->unset($serviceName);
    }

    public function render(string $template, array $params = [])
    {
        ob_start();
        include __DIR__ . '/../../View/' . $template . '.php';
        $content = ob_get_contents();
        ob_end_clean();

        if($template === '404')
        {
            $response = new Response($content, 404, ["HTTP/1.0 404 Not Found"]);
            return $response;
        }

        return $content;
    }

    public function get(string $pattern, callable $callable) {
        $this->registerRoute(self::GET, $pattern, $callable);

        return $this; //Un App est renvoyé
    }

    public function post(string $pattern, callable $callable) {
        $this->registerRoute(self::POST, $pattern, $callable);
        return $this;
    }

    public function run(Request $request = null) {
        if($request === null) {
            $request = Request::createFromGlobals();
        }
        $method = $request->getMethod();
        $uri = $request->getUri();
        var_dump($uri);
        var_dump($method);
        foreach($this->routes as $route) {
            if($route->match($method, $uri)) {
                return $this->process($route, $request);
            }
        }

        throw new \Error('No routes available for this uri');
    }

    private function process(Route $route, Request $request) {
        try 
        {
            $arguments = $route->getArguments();
            array_unshift($arguments, $request);
            $content = call_user_func_array($route->getCallable(), $arguments);

            if($content instanceof Response) {
                $content->send();
                return;
            }

            $response = new Response($content, $this->statusCode ?? 200);
            $response->send();
            
        } catch(\Exception $e) {
            throw new \Error('There was an error during the processing of your request');
        }
    }

    private function registerRoute(string $method, string $pattern, callable $callable) {
        $this->routes[] = new Route($method, $pattern, $callable);
    }
}