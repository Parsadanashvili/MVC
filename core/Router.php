<?php

namespace Core;

use Core\Request\Request;
use Core\Response\Response;
use ReflectionMethod;

class Router
{
    protected array $routes = [];

    public function get($uri, $callback)
    {
        $this->routes['GET'][$uri] = $callback;
    }

    public function post($uri, $callback)
    {
        $this->routes['POST'][$uri] = $callback;
    }

    public function reslove(Request $request)
    {
        $path = $request->path();
        $method = $request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if(is_string($callback)) {
            return new View($callback);
        }

        if(is_callable($callback)) {
            return call_user_func($callback);
        }

        if(is_array($callback)) {
            $args = $this->getMethodArgs($callback[0], $callback[1]);
            $callback[0] = new $callback[0]();
            return call_user_func($callback, ...$args);
        }

        Response::abort('Not Found');
    }

    protected function getMethodArgs($class, $method)
    {
        $ref = new ReflectionMethod($class, $method);
        $args = [];
        foreach($ref->getParameters() as $param) {
            if($param->getClass()) {
                $args[] = $param->getClass()->newInstance();
            } else if($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                $args[] = null;
            }
        }
        
        return $args;
    }
}