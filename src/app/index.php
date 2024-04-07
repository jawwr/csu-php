<?php

namespace csuPhp\Csu2024;

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

$route = Route::get(
    '/users',
    UserController::class,
    'getUsers'
);
$route->middleware(TestMiddleware::class)->middleware(HelloMiddleWare::class);
$route->handle([]);

Route::post(
    '/users',
    UserController::class,
    'createUser'
);

print_r(Route::$routes);

// $app = new App($config);
// $app->run();