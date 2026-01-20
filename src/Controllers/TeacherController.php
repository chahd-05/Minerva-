<?php
namespace App\Controllers;

use App\Services\AuthService;

class TeacherController {
    private $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    public function dashboard() {
        $this->authService->requireRole('teacher');
        
        $user = $this->authService->getCurrentUser();
        
        include __DIR__ . '/../views/teacher/dashboard.php';
    }
}