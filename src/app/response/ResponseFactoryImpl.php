<?php

namespace csuPhp\Csu2024\response;

use csuPhp\Csu2024\response;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;

class ResponseFactoryImpl implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = ''): ResponseInterface
    {
        return new response\Response($code, $reasonPhrase, [], "", "1.1");
    }
}