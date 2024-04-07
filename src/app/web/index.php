<?php

namespace csuPhp\Csu2024;

use csuPhp\Csu2024\controller\UserController;
use csuPhp\Csu2024\request\CustomRequestFactory;
use csuPhp\Csu2024\response\CustomResponseFactory;
use csuPhp\Csu2024\router\Route;
use csuPhp\Csu2024\web\middleware\HelloMiddleWare;
use csuPhp\Csu2024\web\middleware\TestMiddleware;

require dirname(__DIR__) . '/../../vendor/autoload.php';

$config = [
    'components' => [
        'userController' => UserController::class
    ],
//    'componentScan' => __DIR__,
];
echo "<pre>";
Route::get('/user',
    UserController::class,
    'getUsers',
    [TestMiddleware::class, HelloMiddleWare::class]);

$app = new App($config);
$app->run();