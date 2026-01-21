<?php
namespace App\Controllers;

use App\Services\ClassService;
use App\Services\AuthService;

class ClassController {
    private $classService;
    private $authService;
    
    public function __construct() {
        $this->classService = new ClassService();
        $this->authService = new AuthService();
    }
    
    public function index() {
        $this->authService->requireRole('teacher');
        
        $classes = $this->classService->findByTeacherId($_SESSION['user_id']);
        
        include __DIR__ . '/../views/teacher/classroom.php';
    }
    
    public function store() {
        $this->authService->requireRole('teacher');
        
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        
        $this->classService->create($name, $description, $_SESSION['user_id']);
        
        header('Location: /teacher/classrooms');
        exit;
    }
}
