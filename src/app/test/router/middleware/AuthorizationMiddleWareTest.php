<?php

namespace csuPhp\Csu2024\test\router\middleware;

use core\router\middleware\AuthorizationMiddleWare;
use PHPUnit\Framework\TestCase;

class AuthorizationMiddleWareTest extends TestCase
{
    public function testIsAuthorizedWithCorrectCredentials(): void
    {
        $middleware = new AuthorizationMiddleWare();
        $authorizationHeader = base64_encode('admin:password');
        $params = ['Authorization' => 'Basic ' . $authorizationHeader];
        $this->assertTrue($middleware->handle($params));
    }

    public function testIsAuthorizedWithIncorrectCredentials(): void
    {
        $middleware = new AuthorizationMiddleWare();
        $authorizationHeader = base64_encode('admin:wrong_password');
        $params = ['Authorization' => 'Basic ' . $authorizationHeader];
        $this->assertFalse($middleware->handle($params));
    }

    /*public function testIsAuthorizedWithoutAuthorizationHeader(): void
    {
        $middleware = new AuthorizationMiddleWare();
        $params = [];
        $this->assertFalse($middleware->handle($params));
    }*/

    public function testIsAuthorizedWithNonBasicAuthorizationHeader(): void
    {
        $middleware = new AuthorizationMiddleWare();
        $authorizationHeader = base64_encode('admin:password');
        $params = ['Authorization' => 'Bearer ' . $authorizationHeader];
        $this->assertFalse($middleware->handle($params));
    }
}
