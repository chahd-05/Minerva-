<?php
namespace App\Services;

use App\Models\User;

class AuthService {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            throw new \Exception("Email et mot de passe sont requis");
        }
        
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            throw new \Exception("Utilisateur non trouvé");
        }
        
        if (!password_verify($password, $user['password'])) {
            throw new \Exception("Mot de passe incorrect");
        }
        
        return $user;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return $this->userModel->findById($_SESSION['user_id']);
    }
    
    public function getUserRole() {
        return $_SESSION['role'] ?? null;
    }
    
    public function logout() {
        session_destroy();
    }
    
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }
    
    public function requireRole($requiredRole) {
        $this->requireAuth();
        
        if ($this->getUserRole() !== $requiredRole) {
            echo "Accès interdit";
            exit;
        }
    }
}