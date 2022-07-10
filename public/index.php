<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\IndexController;
use Core\Application;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application(dirname(__DIR__));

$app->router->get('/', [IndexController::class, 'index']);

$app->router->get('/login', [LoginController::class, 'index']);

$app->router->post('/login', [LoginController::class, 'handleLogin']);

$app->run();