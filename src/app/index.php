<?php

namespace csuPhp\Csu2024;

use core\app\App;
use core\router\middleware\AuthorizationMiddleWare;
use core\router\route\Route;
use csuPhp\Csu2024\controller\TestController;
use csuPhp\Csu2024\controller\UserController;

require dirname(__DIR__) . '/../vendor/autoload.php';

$config = [
    'componentScan' => __DIR__,
];

Route::get(
    '/testWork',
    UserController::class,
    'getUsers'
);

Route::get(
    '/testException',
    TestController::class,
    'testException'
);

Route::get(
    '/tauth',
    TestController::class,
    'test'
)->middleware(AuthorizationMiddleWare::class);

$app = new App($config);
$app->run();