<?php
namespace App\Controllers;

use App\Models\User;

class AuthController {
    private $user;
    
    public function __construct() {
        $this->user = new User();
    }
    
    public function showLogin() {
        include __DIR__ . '/../views/auth/login.php';
    }
    
  
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? '';
            
            if (empty($email) || empty($password)) {
                $error = "Veuillez remplir tous les champs";
                include __DIR__ . '/../views/auth/login.php';
                return;
            }
            
            $user = $this->user->findByEmail($email);
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $user['role'];
                if ($role === 'teacher') {
                    header('Location: /../views/teacher/dashboard');
                    exit;
                }
                elseif ($role === 'student') {
                    header('Location: /../views/student/dashboard');
                    exit;
                }
               
                exit;
            } else {
                $error = "Email ou mot de passe incorrect";

             
            }
        }
    }
    

    
    public function logout() {
        session_destroy();
        header('Location: /../views/auth/login');
        exit;
    }
}
