<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;
use App\Core\Database;
use App\Core\Auth;
use App\Controllers\AuthController;
use App\Controllers\StudentController;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;

$app = new Application();

$app->router->get('/', function(){
    include __DIR__ . '/../app/views/home.php';
});

$app->router->get('/login', ['AuthController', 'showLogin']);
$app->router->post('/login', ['AuthController', 'login']);

$app->router->get('/register', ['AuthController', 'showRegister']);
$app->router->post('/register', ['AuthController', 'register']);

$app->router->get('/logout', ['AuthController', 'logout']);

$app->router->get('/student/dashboard', ['StudentController', 'dashboard']);
$app->router->get('/student/course/{id}', function($path) {
    $id = basename($path);
    $controller = new StudentController();
    $controller->courseDetails($id);
});

$app->router->post('/student/enroll/{id}', function($path) {
    $id = basename($path);
    $controller = new StudentController();
    $controller->enroll($id);
});

$app->router->post('/student/unenroll/{id}', function($path) {
    $id = basename($path);
    $controller = new StudentController();
    $controller->unenroll($id);
});

$app->run();

        



