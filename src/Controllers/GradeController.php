<?php
namespace App\Controllers;

use App\Models\Grade;

class GradeController {

    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        $model = new Grade();
        $assignedWorks = $model->getTeacherAssignedWorks($_SESSION['user_id']);
        
        require __DIR__ . '/../Views/teacher/grade.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        $model = new Grade();
        $model->gradeSubmission(
            $_POST['submission_id'],
            $_POST['score'],
            $_POST['comment']
        );

        header('Location: /teacher/grade');
        exit;
    }
}
