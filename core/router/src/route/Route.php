<?php

namespace core\router\route;

use core\router\middleware\IMiddleware;
use core\router\middleware\MiddlewareBuilder;
use Exception;

class Route
{
    public static array $routes = [];
    private string $name;
    private string $requestMethod;
    private MiddlewareBuilder $middleware;

    public array $test = [
        '/test' => [
            'POST' => [
                'class' => 'SomeController',
                'method' => 'someMethod',
                'middleware' => 'TestMiddleware'
            ]
        ]
    ];

    private function __construct(string $name, string $requestMethod)
    {
        $this->name = $name;
        $this->requestMethod = $requestMethod;
    }

    public static function get(string $path, string $className, string $methodName): Route
    {
        return self::createRote('GET', $path, $className, $methodName);
    }

    public static function post(string $path, string $className, string $methodName): Route 
    {
        return self::createRote('POST', $path, $className, $methodName);
    }

    public static function put(string $path, string $className, string $methodName): Route 
    {
        return self::createRote('PUT', $path, $className, $methodName);
    }

    public static function delete(string $path, string $className, string $methodName): Route
    {
        return self::createRote('DELETE', $path, $className, $methodName);
    }

    private static function createRote(string $methodType, string $path, string $className, string $methodName): Route
    {
        if (!method_exists($className, $methodName)) {
            throw new Exception("Method $methodName doesn't exist in $className");
        }
        static::$routes[$path][$methodType] = ['class' => $className, 'method' => $methodName];
        return new Route($path, $methodType);
    }

    public function handle(array $params)
    {
        if (!$this->middleware->next($params)) {
            throw new Exception("Reqeuest error handling");
        }
        return null;
    }

    public function middleware(string $name): MiddlewareBuilder {
        if (!class_exists($name)) {
            throw new Exception("Class doesn't exist");
        }
        $impls = class_implements($name);
        if (!isset($impls[IMiddleware::class])) {
            throw new Exception("Class must implements IMiddleware");
        }
        if (!isset($this->middleware)) {
            $this->middleware = new MiddlewareBuilder(new $name());
            self::$routes[$this->name][$this->requestMethod]['middleware'] = $this->middleware;
        } else {
            $this->middleware->middleware($name);
        }
        return $this->middleware;
    }
}