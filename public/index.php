<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;
use App\Controllers\AuthController;
use App\Controllers\TeacherController;
use App\Controllers\StudentController;
use App\Controllers\ClassController;

$app = new Application();

$app->router->get('/', ['AuthController', 'showLogin']);
$app->router->post('/', ['AuthController', 'login']);
$app->router->get('/login', ['AuthController', 'showLogin']);
$app->router->post('/login', ['AuthController', 'login']);
$app->router->get('/logout', ['AuthController', 'logout']);

$app->router->get('/teacher/dashboard', ['TeacherController', 'dashboard']);

$app->router->get('/student/dashboard', ['StudentController', 'dashboard']);

$app->router->get('/teacher/classrooms', ['ClassController', 'index']);
$app->router->post('/teacher/classrooms/store', ['ClassController', 'store']);

$app->run();

        



