<?php

namespace csuPhp\Csu2024\middleware;

use core\router\middleware\IMiddleware;

class HelloMiddleWare implements IMiddleware
{

    public function handle(array $params): bool
    {
        echo "PHP goes 'brbrbr'";
        echo "<br>";
        return true;
    }
}