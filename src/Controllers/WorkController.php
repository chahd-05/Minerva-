<?php 
namespace App\Controllers;

use App\Services\WorkService;
use App\Services\AuthService;

class WorkController {
    private $workService;
    private $classModel;
    private $authService;

    public function __construct() {
        $this->workService = new WorkService();
        $this->authService = new AuthService();
    }

    public function createForm() {
        $this->authService->requireRole('teacher');
    
        $classModel = new \App\Models\ClassRoom();
        $classes = $classModel->findByTeacherId($_SESSION['user_id']);
        
        // Pre-select class if class_id is provided
        $selectedClassId = $_GET['class_id'] ?? null;
        
        include __DIR__ . '/../Views/teacher/create_work.php';
    }

    public function store() {
        $this->authService->requireRole('teacher');

        $classroom_id = $_POST['classroom_id'];
        $title        = $_POST['title'];
        $description  = $_POST['description'];
        $deadline     = $_POST['deadline'];
        $file         = $_FILES['file'] ?? null;

        try {
            $this->workService->create(
                $classroom_id,
                $title,
                $description,
                $deadline,
                $file
            );
            
            $_SESSION['success'] = "Devoir créé avec succès !";
            
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /teacher/create-work');
        exit;
    }
}
