<?php
namespace App\Controllers;

use App\Models\Submission;

class SubmissionController {

    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: /login');
            exit;
        }

        $model = new Submission();
        $works = $model->getAssignedWorks($_SESSION['user_id']);

        require __DIR__ . '/../Views/student/submissions.php';
    }

    public function store() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
            header('Location: /login');
            exit;
        }

        $work_id = $_POST['work_id'];
        $content = trim($_POST['content']);

        $model = new Submission();

        if ($model->alreadySubmitted($work_id, $_SESSION['user_id'])) {
            $_SESSION['error'] = "Travail déjà soumis";
        } else {
            $model->submit($work_id, $_SESSION['user_id'], $content);
            $_SESSION['success'] = "Travail soumis avec succès";
        }

        header('Location: /student/submissions');
        exit;
    }
}
