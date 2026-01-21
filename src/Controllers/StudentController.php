<?php
namespace App\Controllers;

use App\Services\StudentService;
use App\Services\AuthService;
use App\Services\ClassService;
use PDOException;

class StudentController {
    private $studentService;
    private $authService;
    private $classService;

    public function __construct() {
        $this->studentService = new StudentService();
        $this->authService = new AuthService();
        $this->classService = new ClassService();
    }

    public function create() {
        $this->authService->requireRole('teacher');
        
        // Récupérer les classes de l'enseignant
        $classes = $this->classService->findByTeacherId($_SESSION['user_id']);
        
        include __DIR__ . '/../Views/teacher/create_student.php';
    }

    public function store() {
        $this->authService->requireRole('teacher');

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $classId = $_POST['class_id'] ?? null;

        if (!$name || !$email || !$classId) {
            die("Veuillez remplir tous les champs.");
        }

        try {
            $studentId = $this->studentService->createStudent($name, $email, $classId);
            
            $_SESSION['success'] = "L'étudiant $name a été créé avec succès. Un email avec ses identifiants lui a été envoyé.";
            
            header("Location: /teacher/students/create");
            exit;
        } catch (PDOException $e) {
            die("Erreur lors de la création: " . $e->getMessage());
        }
    }
}
