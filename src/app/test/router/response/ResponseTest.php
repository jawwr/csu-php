<?php

namespace core\router\response;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseTest extends TestCase
{
    public function testGetStatusCode(): void
    {
        $response = new Response(200, 'OK', [], null, '1.1');
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testGetReasonPhrase(): void
    {
        $response = new Response(200, 'OK', [], null, '1.1');
        $this->assertSame('OK', $response->getReasonPhrase());
    }

    public function testGetBody(): void
    {
        $stream = $this->getMockBuilder(StreamInterface::class)->getMock();
        $response = new Response(200, 'OK', [], $stream, '1.1');
        $this->assertInstanceOf(StreamInterface::class, $response->getBody());
    }

    public function testWithStatus(): void
    {
        $response = new Response(200, 'OK', [], null, '1.1');
        $newResponse = $response->withStatus(404, 'Not Found');

        $this->assertInstanceOf(ResponseInterface::class, $newResponse);
        $this->assertSame(404, $newResponse->getStatusCode());
        $this->assertSame('Not Found', $newResponse->getReasonPhrase());
    }

    public function testGetHeaders(): void
    {
        $headers = ['Content-Type' => 'text/html'];
        $response = new Response(200, 'OK', $headers, null, '1.1');
        $this->assertSame($headers, $response->getHeaders());
    }

    public function testHasHeader(): void
    {
        $headers = ['Content-Type' => 'text/html'];
        $response = new Response(200, 'OK', $headers, null, '1.1');
        $this->assertTrue($response->hasHeader('Content-Type'));
    }

    public function testGetHeader(): void
    {
        $headers = ['Content-Type' => 'text/html'];
        $response = new Response(200, 'OK', $headers, null, '1.1');
        $this->assertTrue($response->hasHeader('Content-Type'));
    }

    public function testGetHeaderLine(): void
    {
        $response = new Response(200, 'OK', [], null, '1.1');
        $this->assertSame('', $response->getHeaderLine('Content-Type'));
    }

    public function testWithHeader(): void
    {
        $response = new Response(200, 'OK', [], null, '1.1');
        $newResponse = $response->withHeader('Content-Type', 'text/plain');
        $this->assertInstanceOf(ResponseInterface::class, $newResponse);
        $this->assertSame(['Content-Type' => ['text/plain']], $newResponse->getHeaders());
    }

    public function testWithAddedHeader(): void
    {
        $response = new Response(200, 'OK', ['Content-Type' => ['text/html']], null, '1.1');
        $newResponse = $response->withAddedHeader('Authorization', 'Bearer token');
        $this->assertInstanceOf(ResponseInterface::class, $newResponse);
        $this->assertSame(['Content-Type' => ['text/html'], 'Authorization' => ['Bearer token']], $newResponse->getHeaders());
    }

    public function testWithoutHeader(): void
    {
        $response = new Response(200, 'OK', ['Content-Type' => ['text/html']], null, '1.1');
        $newResponse = $response->withoutHeader('Content-Type');
        $this->assertInstanceOf(ResponseInterface::class, $newResponse);
        $this->assertSame([], $newResponse->getHeaders());
    }

    public function testGetProtocolVersion(): void
    {
        $response = new Response(200, 'OK', [], null, '1.1');
        $this->assertSame('1.1', $response->getProtocolVersion());
    }

    public function testWithProtocolVersion(): void
    {
        $response = new Response(200, 'OK', [], null, '1.1');
        $newResponse = $response->withProtocolVersion('2.0');
        $this->assertInstanceOf(ResponseInterface::class, $newResponse);
        $this->assertSame('2.0', $newResponse->getProtocolVersion());
    }
}
