<?php

namespace core\router\middleware;

use Exception;

class Middleware
{
    private IMiddleware $action;

    public function __construct(string $name)
    {
        if (!class_exists($name)) {
            throw new Exception("Class $name doesn't exist");
        }
        if (!class_implements($name)[IMiddleware::class]) {
            throw new Exception("Class doesn't implement IMiddleware");
        }
        $this->action = new $name();
    }

    public function next(array $params): bool
    {
        return $this->action->handle($params);
    }
}