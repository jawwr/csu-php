<?php

namespace csuPhp\Csu2024\test\router\middleware;

use core\router\middleware\IMiddleware;
use core\router\middleware\MiddlewareBuilder;
use PHPUnit\Framework\TestCase;

class MiddlewareBuilderTest extends TestCase
{
    public function testMiddlewareChainFailure(): void
    {
        $middleware1 = $this->createMock(IMiddleware::class);
        $middleware1->expects($this->once())
            ->method('handle')
            ->willReturn(false);

        $middleware2 = $this->createMock(IMiddleware::class);
        $middleware2->expects($this->never())
            ->method('handle');

        $middlewareBuilder = new MiddlewareBuilder($middleware1);
        $middlewareBuilder->middleware(get_class($middleware2));

        $this->assertFalse($middlewareBuilder->next([]));
    }
}
