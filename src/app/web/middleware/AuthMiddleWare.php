<?php

namespace csuPhp\Csu2024\web\middleware;

use csuPhp\Csu2024\middleware\IMiddleware;

class AuthMiddleWare implements IMiddleware
{
    private $registeredUsers = [
        'username' => 'admin',
        'password' => 'password'
    ];

    public function handle(array $params): bool {
        return $this->checkAuth($params['Authorization']);
    }

    private function checkAuth(string $authorizationHeader): bool
    {
        if ($authorizationHeader && str_starts_with($authorizationHeader, 'Basic')) {
            list($username, $password) = explode(':', base64_decode(substr($authorizationHeader, 6)));
            if ($username === $this->registeredUsers['username'] && $password === $this->registeredUsers['password']) {
                return true;
            }
        }
        return false;
    }
}