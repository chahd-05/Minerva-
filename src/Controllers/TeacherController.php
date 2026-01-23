<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Services\ClassService;
use App\Models\Work;

class TeacherController {
    private $authService;
    private $classService;
    
    public function __construct() {
        $this->authService = new AuthService();
        $this->classService = new ClassService();
    }
    
    public function dashboard() {
        $this->authService->requireRole('teacher');
        
        $user = $this->authService->getCurrentUser();
        
        include __DIR__ . '/../views/teacher/dashboard.php';
    }
}