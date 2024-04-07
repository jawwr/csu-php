<?php

namespace csuPhp\Csu2024\router;

use csuPhp\Csu2024\components\BaseComponent;
use csuPhp\Csu2024\request\RequestFactoryImpl;
use csuPhp\Csu2024\response\ResponseFactoryImpl;
use Exception;

class Router extends BaseComponent
{
    private $responseFactory;
    private $requestFactory;
    private $routes = [];
    private $rules = [];

    public function __construct(RequestFactoryImpl $requestFactory, ResponseFactoryImpl $responseFactory)
    {
        parent::__construct();
        $this->responseFactory = $responseFactory;
        $this->requestFactory = $requestFactory;
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

    public function run(): string
    {
        $request = $this->requestFactory->createRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
        $path = $_SERVER["path"];
        $result = "";
        foreach ($this->rules as $rule => $handler) {
            if (preg_match($rule, $path) != 0) {
                $result = call_user_func($handler, $request);
                break;
            }
        }
        if (empty($result)) {
            throw new Exception();
        }
        return $result;
    }

    protected function init(): void
    {
        // $this->rules = ["index" => fn($request) => new TextResponse("pupupu"),];
        $this->rules = [];
    }
}