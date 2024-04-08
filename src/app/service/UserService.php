<?php

namespace csuPhp\Csu2024\service;

use core\di\components\BaseComponent;

class UserService extends BaseComponent {
    public function testCreateUser() {
        return ["message" => "user created"];
    }
}