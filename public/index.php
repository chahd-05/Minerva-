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
use App\Controllers\AttendanceController;
use App\Controllers\StatisticsController;
use App\Controllers\ChatController;
use App\Controllers\WorkController;

$app = new Application();

$app->router->get('/', ['AuthController', 'showLogin']);
$app->router->post('/', ['AuthController', 'login']);
$app->router->get('/login', ['AuthController', 'showLogin']);
$app->router->post('/login', ['AuthController', 'login']);
$app->router->get('/logout', ['AuthController', 'logout']);

$app->router->get('/teacher/dashboard', ['TeacherController', 'dashboard']);

$app->router->get('/teacher/create-work', ['WorkController', 'createForm']);
$app->router->post('/teacher/create-work', ['WorkController', 'store']);

$app->router->get('/student/dashboard', ['StudentController', 'dashboard']);
$app->router->get('/student/my-class', ['StudentController', 'myClass']);
$app->router->get('/student/grades', ['StudentController', 'grades']);
$app->router->get('/student/submissions', ['StudentController', 'submissions']);
$app->router->post('/student/submissions', ['StudentController', 'submit']);

$app->router->get('/teacher/classrooms', ['ClassController', 'index']);
$app->router->post('/teacher/classrooms/store', ['ClassController', 'store']);
$app->router->get('/teacher/classrooms/assign-students', ['ClassController', 'assignStudents']);
$app->router->post('/teacher/classrooms/assign-student', ['ClassController', 'assignStudent']);
$app->router->get('/teacher/classrooms/remove-student', ['ClassController', 'removeStudent']);
$app->router->get('/teacher/attendance', ['AttendanceController', 'index']);
$app->router->get('/teacher/attendance/take', ['AttendanceController', 'take']);
$app->router->post('/teacher/attendance/save', ['AttendanceController', 'save']);
$app->router->get('/teacher/attendance/stats', ['AttendanceController', 'stats']);
$app->router->get('/teacher/statistics', ['StatisticsController', 'index']);
$app->router->get('/teacher/statistics/class', ['StatisticsController', 'classStats']);
$app->router->get('/chat', ['ChatController', 'index']);
$app->router->get('/chat/classroom', ['ChatController', 'classroom']);
$app->router->post('/chat/send', ['ChatController', 'sendMessage']);
$app->router->get('/chat/refresh', ['ChatController', 'refreshMessages']);
$app->router->get('/teacher/students/create', ['StudentController', 'create']);
$app->router->post('/teacher/students/store', ['StudentController', 'store']);

$app->router->get('/teacher/assignwork', ['WorkAssignmentController', 'createForm']);
$app->router->post('/teacher/assignwork', ['WorkAssignmentController', 'store']);

$app->router->get('/teacher/grade', ['WorkAssignmentController', 'grading']);
$app->router->post('/teacher/grade', ['WorkAssignmentController', 'grade']);

$app->run();

        



