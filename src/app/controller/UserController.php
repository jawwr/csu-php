<?php

namespace csuPhp\Csu2024\controller;

use core\di\components\BaseComponent;
use csuPhp\Csu2024\service\UserService;

class UserController extends BaseComponent {
    private UserService $service;
    
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function getUsers() {
        return ["message" => "it's work"];
    }

    public function createUser() {
        return $this->service->testCreateUser();
    }
}