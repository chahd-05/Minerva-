<?php 
namespace App\Controllers;

use App\Services\WorkService;

class WorkController {
    private $workService;
    private $classModel;

    public function __construct() {
        $this->workService = new WorkService();

    }

    public function createForm() {
        
        if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }
    
        $classModel = new \App\Models\ClassRoom();
        $classes = $classModel->findByTeacherId($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/teacher/create_work.php';
    }

    public function store() {
        if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

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

        header('Location: /teacher/creatework');
        exit;
    }
}
