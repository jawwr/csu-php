<?php

namespace csuPhp\Csu2024\test\router\request;

use PHPUnit\Framework\TestCase;
use core\router\request\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class RequestTest extends TestCase
{
    public function testGetMethod(): void
    {
        $request = new Request('GET', 'http://example.com', [], null, '1.1');
        $this->assertSame('GET', $request->getMethod());
    }

    public function testWithMethod(): void
    {
        $request = new Request('GET', 'http://example.com', [], null, '1.1');
        $newRequest = $request->withMethod('POST');
        $this->assertInstanceOf(RequestInterface::class, $newRequest);
        $this->assertSame('POST', $newRequest->getMethod());
    }
}
