<?php
namespace App\Controllers;

use App\Core\BaseController;

class AuthController extends BaseController {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function showLogin() {
        if ($this->authService->isLoggedIn()) {
            $this->redirectToDashboard();
            return;
        }
        
        include __DIR__ . '/../views/auth/login.php';
    }
    
    public function login() {
        $error = null;
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception("Méthode non autorisée");
            }
            
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $user = $this->authService->login($email, $password);
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            
            $this->redirectToDashboard();
            
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
        
        include __DIR__ . '/../views/auth/login.php';
    }
    
    public function logout() {
        $this->authService->logout();
        header('Location: /login');
        exit;
    }
    
    private function redirectToDashboard() {
        $role = $this->authService->getUserRole();
        
        if ($role === 'teacher') {
            header('Location: /teacher/dashboard');
        } elseif ($role === 'student') {
            header('Location: /student/dashboard');
        } else {
            header('Location: /login');
        }
        exit;
    }
}
