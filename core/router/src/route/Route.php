<?php

namespace core\router\route;

use core\router\middleware\Middleware;

class Route
{
    public static array $routes = [];//TODO фиксировать все новые роуты с middleware

    /**
     * @throws \Exception
     */
    public static function get(string $path, string $className, string $methodName, array $middlewares = [])
    {
        $roteMiddlewares = [];
        foreach ($middlewares as $middleware) {
            $roteMiddlewares[] = new Middleware($middleware);
        }
        static::$routes = [$path => [$className, $methodName, 'middleware' => $roteMiddlewares]];
    }

    public function start(array $params): bool
    {
        return static::$routes[0]->next($params);
    }
}