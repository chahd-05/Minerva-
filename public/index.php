<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;
use App\Core\Database;
use App\Core\Auth;
use App\Controllers\AuthController;




$app = new Application();

$app->router->get('/', ['AuthController', 'showLogin']);

$app->router->get('/login', ['AuthController', 'showLogin']);
$app->router->post('/login', ['AuthController', 'login']);



$app->router->get('/logout', ['AuthController', 'logout']);



$app->run();

        



