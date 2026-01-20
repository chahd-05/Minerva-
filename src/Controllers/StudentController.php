<?php
namespace App\Controllers;

use App\Services\AuthService;

class StudentController {
    private $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    public function dashboard() {
        $this->authService->requireRole('student');
        
        $user = $this->authService->getCurrentUser();
        
        include __DIR__ . '/../views/student/dashboard.php';
    }
}