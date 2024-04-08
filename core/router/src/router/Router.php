<?php

namespace core\router\router;

use core\di\components\BaseComponent;
use core\router\request\RequestDispatcher;
use core\router\request\RequestFactoryImpl;
use core\router\response\ResponseFactoryImpl;
use core\router\route\Route;
use Exception;

class Router extends BaseComponent
{
    private $responseFactory;
    private $requestFactory;
    private $requestDispatcher;
    private $routes;

    public function __construct(RequestFactoryImpl $requestFactory, ResponseFactoryImpl $responseFactory, RequestDispatcher $requestDispatcher)
    {
        parent::__construct();
        $this->responseFactory = $responseFactory;
        $this->requestFactory = $requestFactory;
        $this->requestDispatcher = $requestDispatcher;
        $this->routes = Route::$routes ?? [];
    }

    public function addRoute($method, $path, $handler)
    {
        $this->routes[$method][$path] = $handler;
    }

    public function start(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (!isset($this->routes[$uri])) {
            $this->requestDispatcher->sendResponse(404);
            return;
        }
        $method = $_SERVER["REQUEST_METHOD"];
        if (!isset($this->routes[$uri][$method])) {
            $this->requestDispatcher->sendResponse(404);
            return;
        }
        $handler = $this->routes[$uri][$method];

        $this->requestDispatcher->handle(getallheaders(), $handler);
    }
}