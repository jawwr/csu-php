<?php

namespace csuPhp\Csu2024;

use core\app\App;
use core\router\middleware\AuthorizationMiddleWare;
use core\router\route\Route;
use csuPhp\Csu2024\controller\TestController;
use csuPhp\Csu2024\controller\UserController;
use csuPhp\Csu2024\middleware\HelloMiddleWare;

require dirname(__DIR__) . '/../vendor/autoload.php';

$config = [
    'componentScan' => __DIR__,
];
// echo "<pre>";

$route = Route::get(
    '/index',
    UserController::class,
    'getUsers'
);
$username = base64_encode("admin:password");
$requestHeader = [
    'Authorization' => "Basic $username",
];

$route->middleware(HelloMiddleWare::class)
// ->middleware(AuthorizationMiddleWare::class)
// ->middleware(ContentTypeMiddleWare::class)
;
// $route->handle($requestHeader);

Route::post(
    '/users',
    UserController::class,
    'createUser'
);

Route::post(
    '/test',
    TestController::class,
    'testException'
)->middleware(AuthorizationMiddleWare::class);

// print_r(Route::$routes);

$app = new App($config);
$app->run();