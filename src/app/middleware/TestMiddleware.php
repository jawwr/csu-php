<?php

namespace csuPhp\Csu2024\middleware;

use core\router\middleware\IMiddleware;

class TestMiddleware implements IMiddleware
{

    public function handle(array $params): bool
    {
        echo "1234";
        echo "<br>";
        return true;
    }
}