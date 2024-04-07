<?php

namespace core\router\request;

use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;

class RequestFactoryImpl implements RequestFactoryInterface
{
    public function createRequest(string $method = "GET", $uri = ''): RequestInterface
    {
        return new Request($method, $uri, [], "", "1.1");
    }
}