<?php

namespace csuPhp\Csu2024\test\router\middleware;

use core\router\middleware\AuthenticationMiddleWare;
use PHPUnit\Framework\TestCase;

class AuthenticationMiddleWareTest extends TestCase
{
    public function testHandleValidCredentials(): void
    {
        $middleware = new AuthenticationMiddleWare();
        $params = ['Authentication' => 'Basic ' . base64_encode('admin:password')];

        $this->assertTrue($middleware->handle($params));
    }

    public function testHandleInvalidCredentials(): void
    {
        $middleware = new AuthenticationMiddleWare();
        $params = ['Authentication' => 'Basic ' . base64_encode('invalid:credentials')];

        $this->assertFalse($middleware->handle($params));
    }

    public function testHandleNoAuthenticationHeader(): void
    {
        $middleware = new AuthenticationMiddleWare();
        $params = [];

        $this->assertFalse($middleware->handle($params));
    }
}
