<?php

namespace csuPhp\Csu2024\web\middleware;

use csuPhp\Csu2024\middleware\IMiddleware;

class HelloMiddleWare implements IMiddleware
{

    public function handle(array $params): bool
    {
        echo "br";
        echo "<br>";
        return true;
    }
}