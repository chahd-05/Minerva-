<?php
namespace App\Controllers;

use App\Services\ClassService;
use App\Services\AuthService;

class ClassController {
    private $classService;
    private $authService;
    
    public function __construct() {
        $this->classService = new ClassService();
        $this->authService = new AuthService();
    }
    
    public function index() {
        $this->authService->requireRole('teacher');
        
        $classes = $this->classService->findByTeacherId($_SESSION['user_id']);
        
        include __DIR__ . '/../views/teacher/classroom.php';
    }
    
    public function store() {
        $this->authService->requireRole('teacher');
        
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        
        $this->classService->create($name, $description, $_SESSION['user_id']);
        
        header('Location: /teacher/classrooms');
        exit;
    }
    
    public function assignStudents() {
        $this->authService->requireRole('teacher');
        
        $classId = $_GET['id'] ?? null;
        if (!$classId) {
            header('Location: /teacher/classrooms');
            exit;
        }
        
        $classModel = new \App\Models\ClassRoom();
        $class = $classModel->findById($classId);
        
        if (!$class || $class['teacher_id'] != $_SESSION['user_id']) {
            header('Location: /teacher/classrooms');
            exit;
        }
        
        $assignedStudents = $classModel->getByClassroom($classId);
        $unassignedStudents = $classModel->getUnassignedStudents($_SESSION['user_id'], $classId);
        
        include __DIR__ . '/../views/teacher/assign_students.php';
    }
    
    public function assignStudent() {
        $this->authService->requireRole('teacher');
        
        $classId = $_POST['class_id'] ?? null;
        $studentId = $_POST['student_id'] ?? null;
        
        if (!$classId || !$studentId) {
            $_SESSION['error'] = "Paramètres manquants";
            header("Location: /teacher/classrooms/assign-students?id=$classId");
            exit;
        }
        
        $classModel = new \App\Models\ClassRoom();
        $class = $classModel->findById($classId);
        
        if (!$class || $class['teacher_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Classe non trouvée ou accès non autorisé";
            header('Location: /teacher/classrooms');
            exit;
        }
        
        if ($classModel->isStudentAssigned($classId, $studentId)) {
            $_SESSION['error'] = "L'étudiant est déjà assigné à cette classe";
        } else {
            if ($classModel->assignStudent($classId, $studentId)) {
                $_SESSION['success'] = "L'étudiant a été assigné avec succès";
            } else {
                $_SESSION['error'] = "Erreur lors de l'assignation";
            }
        }
        
        header("Location: /teacher/classrooms/assign-students?id=$classId");
        exit;
    }
    
    public function removeStudent() {
        $this->authService->requireRole('teacher');
        
        $classId = $_POST['class_id'] ?? null;
        $studentId = $_POST['student_id'] ?? null;
        
        if (!$classId || !$studentId) {
            $_SESSION['error'] = "Paramètres manquants";
            header("Location: /teacher/classrooms/assign-students?id=$classId");
            exit;
        }
        
        $classModel = new \App\Models\ClassRoom();
        $class = $classModel->findById($classId);
        
        if (!$class || $class['teacher_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Classe non trouvée ou accès non autorisé";
            header('Location: /teacher/classrooms');
            exit;
        }
        
        if ($classModel->removeStudent($classId, $studentId)) {
            $_SESSION['success'] = "L'étudiant a été retiré de la classe avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors du retrait";
        }
        
        header("Location: /teacher/classrooms/assign-students?id=$classId");
        exit;
    }
}
