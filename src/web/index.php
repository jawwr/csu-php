<?php

namespace csuPhp\Csu2024;

use csuPhp\Csu2024\request\CustomRequestFactory;
use csuPhp\Csu2024\response\CustomResponseFactory;
use csuPhp\Csu2024\router\Router;

require dirname(__DIR__) . '/../vendor/autoload.php';

$config = [
    'components' => [
        'router' => Router::class,
        'customRequestFactory' => CustomRequestFactory::class,
        'customResponseFactory' => CustomResponseFactory::class
//        'userController' => UserController::class
    ]
];

$app = new App($config);
$app->run();