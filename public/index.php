<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement depuis .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

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

$app->router->get('/teacher/create-work', ['TeacherController', 'createWork']);
$app->router->post('/teacher/create-work', ['TeacherController', 'storeWork']);

$app->router->get('/student/dashboard', ['StudentController', 'dashboard']);
$app->router->get('/student/courses', ['StudentController', 'courses']);
$app->router->get('/student/grades', ['StudentController', 'grades']);

$app->router->get('/teacher/classrooms', ['ClassController', 'index']);
$app->router->post('/teacher/classrooms/store', ['ClassController', 'store']);
$app->router->get('/teacher/students/create', ['StudentController', 'create']);
$app->router->post('/teacher/students/store', ['StudentController', 'store']);
$app->router->get('/teacher/creatework', ['WorkController', 'createForm']);
$app->router->post('/teacher/creatework', ['WorkController', 'store']);

$app->router->get('/teacher/assignwork', ['WorkAssignmentController', 'createForm']);
$app->router->post('/teacher/assignwork', ['WorkAssignmentController', 'store']);

$app->router->get('/student/submissions', ['SubmissionController', 'index']);
$app->router->post('/student/submissions', ['SubmissionController', 'store']);
$app->router->get('/teacher/grade', ['GradeController', 'index']);
$app->router->post('/teacher/grade', ['GradeController', 'store']);


$app->run();

        



