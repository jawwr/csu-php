<?php

namespace csuPhp\Csu2024\test\router\response;

use PHPUnit\Framework\TestCase;
use core\router\response\Response;
use Psr\Http\Message\ResponseInterface;

class ResponseTest extends TestCase
{
    public function testGetStatusCode(): void
    {
        $response = new Response(200, 'OK', [], '', '1.1');
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testWithStatus(): void
    {
        $response = new Response(200, 'OK', [], '', '1.1');
        $newResponse = $response->withStatus(404, 'Not Found');
        $this->assertInstanceOf(ResponseInterface::class, $newResponse);
        $this->assertSame(404, $newResponse->getStatusCode());
        $this->assertSame('Not Found', $newResponse->getReasonPhrase());
    }

    public function testGetHeaders(): void
    {
        $headers = ['Content-Type' => ['application/json']];
        $response = new Response(200, 'OK', $headers, '', '1.1');
        $this->assertSame($headers, $response->getHeaders());
    }
}
