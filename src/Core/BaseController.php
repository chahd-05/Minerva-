<?php
namespace App\Core;

use App\Services\AuthService;

abstract class BaseController {
    protected $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    protected function requireAuth() {
        $this->authService->requireAuth();
    }
    
    protected function requireTeacher() {
        $this->authService->requireRole('teacher');
    }
    
    protected function requireStudent() {
        $this->authService->requireRole('student');
    }
    
    protected function getCurrentUser() {
        return $this->authService->getCurrentUser();
    }
    
    protected function view($path, $data = []) {
        extract($data);
        include __DIR__ . "/../Views/$path";
    }
}
