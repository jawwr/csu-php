<?php

namespace csuPhp\Csu2024\controller;

use core\di\components\BaseComponent;
use csuPhp\Csu2024\service\TestService;

class TestController extends BaseComponent
{
    private TestService $service;
    public function __construct(TestService $service)
    {
        $this->service = $service;
    }

    public function test() {
        $this->service->test();
    }
}