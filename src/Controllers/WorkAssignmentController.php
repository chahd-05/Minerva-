<?php
namespace App\Controllers;

use App\Services\WorkAssignmentService;
use App\Services\GradingService;
use App\Services\AuthService;

class WorkAssignmentController {
    private $workAssignmentService;
    private $gradingService;
    private $authService;
    
    public function __construct() {
        $this->workAssignmentService = new WorkAssignmentService();
        $this->gradingService = new GradingService();
        $this->authService = new AuthService();
    }
    
    public function createForm() {
        $this->authService->requireRole('teacher');
        
        $classModel = new \App\Models\ClassRoom();
        $classes = $classModel->findByTeacherId($_SESSION['user_id']);
        
        $assignmentModel = new \App\Models\WorkAssignment();
        $students = [];
        $works = [];
        $selectedClass = $_GET['class_id'] ?? null;
        $selectedWork = $_GET['work_id'] ?? null;
        
        if ($selectedClass) {
            $works = $assignmentModel->getWorksByClass($selectedClass);
            if ($selectedWork) {
                $students = $assignmentModel->getStudentsByClass($selectedClass, $selectedWork);
            } else {
                $students = $assignmentModel->getStudentsByClass($selectedClass);
            }
        }
        
        require __DIR__ . '/../Views/teacher/assign_work.php';
    }
    
    public function store() {
        $this->authService->requireRole('teacher');
        
        $work_id = $_POST['work_id'];
        $student_ids = $_POST['student_ids'] ?? [];
        $class_id = $_POST['class_id'] ?? $_GET['class_id'];
        
        try {
            // Récupérer tous les étudiants de la classe
            $assignmentModel = new \App\Models\WorkAssignment();
            $allStudents = $assignmentModel->getStudentsByClass($class_id);
            
            $assignedCount = 0;
            $removedCount = 0;
            
            // Parcourir tous les étudiants de la classe
            foreach ($allStudents as $student) {
                $isCurrentlyAssigned = in_array($student['id'], $student_ids);
                $wasPreviouslyAssigned = $assignmentModel->isAlreadyAssigned($work_id, $student['id']);
                
                if ($isCurrentlyAssigned && !$wasPreviouslyAssigned) {
                    // Nouvel assignment
                    $assignmentModel->assignToStudent($work_id, $student['id']);
                    $assignedCount++;
                } elseif (!$isCurrentlyAssigned && $wasPreviouslyAssigned) {
                    // Retrait d'assignment
                    $assignmentModel->removeAssignment($work_id, $student['id']);
                    $removedCount++;
                }
            }
            
            $message = "Travail assigné avec succès";
            if ($assignedCount > 0 && $removedCount > 0) {
                $message = "$assignedCount étudiant(s) assigné(s) et $removedCount retiré(s)";
            } elseif ($assignedCount > 0) {
                $message = "$assignedCount étudiant(s) assigné(s) avec succès";
            } elseif ($removedCount > 0) {
                $message = "$removedCount étudiant(s) retiré(s) avec succès";
            }
            
            $_SESSION['success'] = $message;
            
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        // Rediriger avec les paramètres pour voir l'état mis à jour
        header("Location: /teacher/assignwork?class_id=$class_id&work_id=$work_id");
        exit;
    }

    public function grading() {
        $this->authService->requireRole('teacher');
        
        $assignmentModel = new \App\Models\WorkAssignment();
        $submissions = $assignmentModel->getSubmissionsForTeacher($_SESSION['user_id']);
        
        // Get assigned works for the teacher
        $workModel = new \App\Models\Work();
        $assignedWorks = $workModel->getTeacherAssignedWorks($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/teacher/grade.php';
    }

    public function grade() {
        $this->authService->requireRole('teacher');
        
        $submission_id = $_POST['submission_id'];
        $score = $_POST['score'];
        $comment = $_POST['comment'] ?? null;
        
        try {
            $message = $this->gradingService->gradeSubmission($submission_id, $score, $comment);
            $_SESSION['success'] = $message;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /teacher/grade');
        exit;
    }
}
