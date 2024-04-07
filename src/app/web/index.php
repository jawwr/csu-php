<?php

namespace csuPhp\Csu2024;

use csuPhp\Csu2024\web\controller\UserController;

require dirname(__DIR__) . '/../../vendor/autoload.php';

$config = [
    'components' => [
        'userController' => UserController::class
    ],
//    'componentScan' => __DIR__,
];
echo "<pre>";

$app = new App($config);
$app->run();