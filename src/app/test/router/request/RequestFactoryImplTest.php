<?php

namespace csuPhp\Csu2024\test\router\request;

use PHPUnit\Framework\TestCase;
use core\router\request\RequestFactoryImpl;
use Psr\Http\Message\RequestInterface;

class RequestFactoryImplTest extends TestCase
{
    public function testCreateRequestWithDefaultArguments(): void
    {
        $factory = new RequestFactoryImpl();
        $request = $factory->createRequest();

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertSame('GET', $request->getMethod());
    }

    public function testCreateRequestWithCustomMethod(): void
    {
        $factory = new RequestFactoryImpl();
        $request = $factory->createRequest('POST', 'http://example.com');

        $this->assertInstanceOf(RequestInterface::class, $request);
        $this->assertSame('POST', $request->getMethod());
    }
}
