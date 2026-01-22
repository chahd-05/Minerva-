<?php
namespace App\Controllers;

use App\Services\WorkAssignmentService;
use App\Services\GradingService;

class WorkAssignmentController {
    private $workAssignmentService;
    private $gradingService;
    
    public function __construct() {
        $this->workAssignmentService = new WorkAssignmentService();
        $this->gradingService = new GradingService();
    }
    
    public function createForm() {
        if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }
        
        $classModel = new \App\Models\ClassRoom();
        $classes = $classModel->findByTeacherId($_SESSION['user_id']);
        
        $assignmentModel = new \App\Models\WorkAssignment();
        $students = [];
        $works = [];
        $selectedClass = $_GET['class_id'] ?? null;
        
        if ($selectedClass) {
            $students = $assignmentModel->getStudentsByClass($selectedClass);
            $works = $assignmentModel->getWorksByClass($selectedClass);
        }
        
        require __DIR__ . '/../Views/teacher/assign_work.php';
    }
    
    public function store() {
        if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }
        
        $work_id = $_POST['work_id'];
        $student_ids = $_POST['student_ids'] ?? [];
        
        try {
            $message = $this->workAssignmentService->assignWork($work_id, $student_ids);
            $_SESSION['success'] = $message;
            
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /teacher/assignwork');
        exit;
    }

    public function grading() {
        if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }
        
        $assignmentModel = new \App\Models\WorkAssignment();
        $submissions = $assignmentModel->getSubmissionsForTeacher($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/teacher/grade.php';
    }

    public function grade() {
        if (!isset($_SESSION['user_name']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }
        
        $submission_id = $_POST['submission_id'];
        $score = $_POST['score'];
        $comment = $_POST['comment'] ?? null;
        
        try {
            $message = $this->gradingService->gradeSubmission($submission_id, $score, $comment);
            $_SESSION['success'] = $message;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
        
        header('Location: /teacher/grading');
        exit;
    }
}
