<?php

namespace core\router\router;

use core\di\components\BaseComponent;
use core\router\request\RequestFactoryImpl;
use core\router\response\ResponseFactoryImpl;
use core\router\route\Route;
use Exception;

class Router extends BaseComponent
{
    private $responseFactory;
    private $requestFactory;
    private $routes;

    public function __construct(RequestFactoryImpl $requestFactory, ResponseFactoryImpl $responseFactory)
    {
        parent::__construct();
        $this->responseFactory = $responseFactory;
        $this->requestFactory = $requestFactory;
        $this->routes = Route::$routes ?? [];
    }

    public function addRoute($method, $path, $handler)
    {
        $this->routes[$method][$path] = $handler;
    }

    public function handleRequest()
    {
        $request = $this->requestFactory->createRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        $method = $request->getMethod();
        $path = $request->getUri()->getPath();

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            $response = $handler($request, $this->responseFactory);
        } else {
            $response = $this->responseFactory->createResponse(404);
            $response->getBody()->write('Not Found');
        }
    }

    public function start(): void
    {
        $uri = $_SERVER['REQUEST_URI'];
        if (!isset($this->routes[$uri])) {
            $this->sendResponse(404);
            return;
        }
        $method = $_SERVER["REQUEST_METHOD"];
        if (!isset($this->routes[$uri][$method])) {
            $this->sendResponse(404);
            return;
        }
        $handler = $this->routes[$uri][$method];

        $result = $this->handle($handler);
        $this->sendResponse(body:$result);
    }

    private function sendResponse($code = 200, mixed $body = '') 
    {
        header("Content-Type: application/json");
        http_response_code($code);
        echo json_encode($body);
    }

    private function handle(array $handler): mixed
    {
        if (isset($handler["middleware"])) {
            $middleware = $handler["middleware"];
            if (!$middleware->next(getallheaders())) {
                throw new Exception("Reqeuest error handling");
            }
        }
        $class = $handler['class'];
        $method = $handler['method'];
        return $class->$method();
    }
}