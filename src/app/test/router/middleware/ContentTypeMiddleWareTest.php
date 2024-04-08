<?php

namespace csuPhp\Csu2024\test\router\middleware;

use core\router\middleware\ContentTypeMiddleWare;
use PHPUnit\Framework\TestCase;

class ContentTypeMiddleWareTest extends TestCase
{
    public function testHandleValidJson(): void
    {
        $middleware = new ContentTypeMiddleWare();
        $params = ['Content-Type' => 'application/json'];

        $this->assertTrue($middleware->handle($params));
    }

    public function testHandleNoContentTypeHeader(): void
    {
        $middleware = new ContentTypeMiddleWare();
        $params = [];

        $this->assertTrue($middleware->handle($params));
    }
}
