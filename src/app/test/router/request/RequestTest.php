<?php

namespace csuPhp\Csu2024\test\router\request;

use PHPUnit\Framework\TestCase;
use core\router\request\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
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

    public function testGetProtocolVersion(): void
    {
        $request = new Request('GET', 'http://example.com', [], null, '1.1');
        $this->assertSame('1.1', $request->getProtocolVersion());
    }

    public function testWithProtocolVersion(): void
    {
        $request = new Request('GET', 'http://example.com', [], null, '1.1');
        $newRequest = $request->withProtocolVersion('2.0');
        $this->assertInstanceOf(RequestInterface::class, $newRequest);
        $this->assertSame('2.0', $newRequest->getProtocolVersion());
    }

    public function testGetHeaders(): void
    {
        $headers = ['Content-Type' => 'application/json'];
        $request = new Request('GET', 'http://example.com', $headers, null, '1.1');
        $this->assertSame($headers, $request->getHeaders());
    }

    public function testHasHeader(): void
    {
        $headers = ['Content-Type' => 'application/json'];
        $request = new Request('GET', 'http://example.com', $headers, null, '1.1');
        $this->assertTrue($request->hasHeader('Content-Type'));
    }

    public function testGetHeader(): void
    {
        $request = new Request('GET', 'http://example.com', [], null, '1.1');
        $this->assertSame([], $request->getHeader('Content-Type'));
    }

    public function testGetHeaderLine(): void
    {
        $request = new Request('GET', 'http://example.com', [], null, '1.1');
        $this->assertSame('', $request->getHeaderLine('Content-Type'));
    }

    public function testWithHeader(): void
    {
        $request = new Request('GET', 'http://example.com', [], null, '1.1');
        $newRequest = $request->withHeader('Content-Type', 'application/json');
        $this->assertInstanceOf(RequestInterface::class, $newRequest);
        $this->assertSame(['Content-Type' => ['application/json']], $newRequest->getHeaders());
    }

    public function testWithAddedHeader(): void
    {
        $request = new Request('GET', 'http://example.com', ['Content-Type' => ['application/json']], null, '1.1');
        $newRequest = $request->withAddedHeader('Authorization', 'Bearer token');
        $this->assertInstanceOf(RequestInterface::class, $newRequest);
        $this->assertSame(['Content-Type' => ['application/json'], 'Authorization' => ['Bearer token']], $newRequest->getHeaders());
    }

    public function testWithoutHeader(): void
    {
        $request = new Request('GET', 'http://example.com', ['Content-Type' => ['application/json']], null, '1.1');
        $newRequest = $request->withoutHeader('Content-Type');
        $this->assertInstanceOf(RequestInterface::class, $newRequest);
        $this->assertSame([], $newRequest->getHeaders());
    }

    public function testGetBody(): void
    {
        $stream = $this->getMockBuilder(StreamInterface::class)->getMock();
        $stream->expects($this->once())->method('__toString')->willReturn('Body content');
        $request = new Request('GET', 'http://example.com', [], $stream, '1.1');
        $this->assertSame('Body content', (string) $request->getBody());
    }
}
