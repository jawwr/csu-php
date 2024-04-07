<?php

namespace csuPhp\Csu2024;

use core\app\App;
use csuPhp\Csu2024\controller\UserController;

require dirname(__DIR__) . '/../vendor/autoload.php';

$config = [
//    'components' => [
//        'userController' => UserController::class
//    ],
    'componentScan' => __DIR__,
];
echo "<pre>";
//echo __DIR__;
//echo UserController::class;

$app = new App($config);
$app->run();