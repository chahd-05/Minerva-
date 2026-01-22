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

    public function dashboard() {
        $this->authService->requireRole('student');
        
        $user = $this->authService->getCurrentUser();
        
        include __DIR__ . '/../views/student/dashboard.php';
    }

     public function courses() {
        $this->authService->requireRole('student');
        
        $classModel = new \App\Models\ClassRoom();
        $classrooms = $classModel->findByStudentId($_SESSION['user_id']);
        
        include __DIR__ . '/../views/student/courses.php';
    }

    public function grades() {
        $this->authService->requireRole('student');
        
        $gradeModel = new \App\Models\Grade();
        $grades = $gradeModel->getStudentGrades($_SESSION['user_id']);
        
        include __DIR__ . '/../views/student/grades.php';
    }

    public function create() {
        $this->authService->requireRole('teacher');
        
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

    public function submissions() {
        if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'student') {
            header('Location: /login');
            exit;
        }
        
        $assignmentModel = new \App\Models\WorkAssignment();
        $assignedWorks = $assignmentModel->getStudentAssignedWorks($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/student/submissions.php';
    }
    
    public function submit() {
        if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'student') {
            header('Location: /login');
            exit;
        }
        
        $work_id = $_POST['work_id'];
        $content = $_POST['content'];
        
        $assignmentModel = new \App\Models\WorkAssignment();
        
        if (empty(trim($content))) {
            $_SESSION['error'] = "Le contenu ne peut pas être vide";
        } elseif ($assignmentModel->hasSubmitted($work_id, $_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous avez déjà soumis ce travail";
        } else {
            $assignmentModel->submitWork($work_id, $_SESSION['user_id'], $content);
            $_SESSION['success'] = "Travail soumis avec succès";
        }
        
        header('Location: /student/submissions');
        exit;
    }
}
