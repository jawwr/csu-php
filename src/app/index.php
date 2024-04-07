<?php

namespace csuPhp\Csu2024;

use core\app\App;
use core\router\response\TestResponse;
use core\router\route\Route;
use csuPhp\Csu2024\controller\UserController;
use csuPhp\Csu2024\middleware\HelloMiddleWare;
use csuPhp\Csu2024\middleware\TestMiddleware;

require dirname(__DIR__) . '/../vendor/autoload.php';

$config = [
//    'components' => [
//        'userController' => UserController::class
//    ],
    'componentScan' => __DIR__,
];
echo "<pre>";

Route::get(
    '/users',
    UserController::class,
    'getUsers',
    [HelloMiddleWare::class, TestMiddleware::class]
);

$app = new App($config);
$app->run();