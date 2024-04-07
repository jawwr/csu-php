<?php

namespace core\router\middleware;

class AuthenticationMiddleWare implements IMiddleware
{
    private $registeredUsers = [
        'username' => 'admin',
        'password' => 'password'
    ];

    public function handle(array $params): bool
    {
        return $this->checkAuth($params);
    }

    private function checkAuth($params): bool
    {
        if (isset($params['Authentication'])) {
            $authHeader = $params['Authentication'];
            $authData = explode(' ', $authHeader);
            if (count($authData) == 2 && $authData[0] == 'Basic') {
                $credentials = base64_decode($authData[1]);//приходит целая закодированная base64 строка вида 'username:password'
                list($username, $password) = explode(':', $credentials);
                // Проверяем, правильные ли учетные данные у пользователя
                if ($this->checkCredentials($username, $password)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function checkCredentials($username, $password): bool
    {
        return $this->registeredUsers['username'] === $username && $this->registeredUsers['password'] === $password;
    }
}