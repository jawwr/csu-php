<?php

namespace core\router\middleware;

use Exception;

class MiddlewareBuilder {
    private IMiddleware $middleware;
    private MiddlewareBuilder $next;

    public function __construct(IMiddleware $middleware) {
        $this->middleware = $middleware;
    }

    public function middleware(string $name): MiddlewareBuilder {
        if (!class_exists($name)) {
            throw new Exception("Class $name doesn't exist");
        }
        $impls = class_implements($name);
        if (!isset($impls[IMiddleware::class])) {
            throw new Exception("Class must implements IMiddleware");
        }
        if (!isset($this->next)) {
            $this->next = new MiddlewareBuilder(new $name());
        } else {
            $this->next->middleware($name);
        }
        return $this->next;
    }

    public function next(array $params): bool
    {
        if ($this->middleware->handle($params)) {
            if (isset($this->next)) {
                return $this->next->next($params);
            }
            return true;
        }
        return false;
    }
}