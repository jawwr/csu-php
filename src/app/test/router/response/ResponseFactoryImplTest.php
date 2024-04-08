<?php

namespace csuPhp\Csu2024\test\router\response;

use PHPUnit\Framework\TestCase;
use core\router\response\ResponseFactoryImpl;
use Psr\Http\Message\ResponseInterface;

class ResponseFactoryImplTest extends TestCase
{
    public function testCreateResponseWithDefaultArguments(): void
    {
        $factory = new ResponseFactoryImpl();
        $response = $factory->createResponse();

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('', $response->getReasonPhrase());
    }

    public function testCreateResponseWithCustomCodeAndReasonPhrase(): void
    {
        $factory = new ResponseFactoryImpl();
        $response = $factory->createResponse(404, 'Not Found');

        $this->assertInstanceOf(ResponseInterface::class, $response);
        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Not Found', $response->getReasonPhrase());
    }

}
