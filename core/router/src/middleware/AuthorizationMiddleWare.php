<?php

namespace core\router\middleware;

class AuthorizationMiddleWare implements IMiddleware
{
    private $registeredUsers = [
        'username' => 'admin',
        'password' => 'password'
    ];

    public function handle(array $params): bool {
        if (!isset($params['Authorization'])) {
            return false;
        }
        return $this->isAuthorized($params['Authorization']);
    }

    private function isAuthorized(string $authorizationHeader): bool
    {
        if (!str_starts_with($authorizationHeader, 'Basic')) {
            return false;
        }
        list($username, $password) = explode(':', base64_decode(substr($authorizationHeader, 6)));
        $expected = $this->registeredUsers['username'];
        return $username === $expected
            && $password === $this->registeredUsers['password'];
    }
}