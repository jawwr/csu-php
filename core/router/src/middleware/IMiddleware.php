<?php

namespace core\router\middleware;

interface IMiddleware
{
    public function handle(array $params): bool;
}