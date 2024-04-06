<?php

use csuPhp\Csu2024\App;
use csuPhp\Csu2024\Router;

require dirname(__DIR__) . '/vendor/autoload.php';

$config = [
    'components' => [
        'router' => [
            'class' => Router::class,
        ],
    ]
];

$app = new App($config);
$app->run();