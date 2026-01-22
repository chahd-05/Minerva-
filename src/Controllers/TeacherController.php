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

    public function createWork() {
        $this->authService->requireRole('teacher');
        
        $classes = $this->classService->findByTeacherId($_SESSION['user_id']);
        
        include __DIR__ . '/../Views/teacher/create_work.php';
    }

    public function storeWork() {
        $this->authService->requireRole('teacher');

        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $deadline = $_POST['deadline'] ?? '';
        $classId = $_POST['class_id'] ?? null;

        if (!$title || !$description || !$deadline || !$classId) {
            die("Veuillez remplir tous les champs.");
        }

        try {
            $work = new Work();
            $workId = $work->create($classId, $title, $description, $deadline);
            
            $_SESSION['success'] = "Le devoir '$title' a été créé avec succès.";
            
            header("Location: /teacher/create-work");
            exit;
        } catch (\PDOException $e) {
            die("Erreur lors de la création: " . $e->getMessage());
        }
    }
}