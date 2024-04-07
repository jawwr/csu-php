<?php

namespace csuPhp\Csu2024\middleware;

interface IMiddleware
{
    public function handle(array $params): bool;
}