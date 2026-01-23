<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Models\Attendance;
use App\Models\ClassRoom;

class AttendanceController {
    private $authService;
    private $attendanceModel;
    private $classRoomModel;
    
    public function __construct() {
        $this->authService = new AuthService();
        $this->attendanceModel = new Attendance();
        $this->classRoomModel = new ClassRoom();
    }
    
    // Page principale des présences
    public function index() {
        $this->authService->requireRole('teacher');
        
        $classes = $this->classRoomModel->findByTeacherId($_SESSION['user_id']);
        
        include __DIR__ . '/../Views/teacher/attendance_index.php';
    }
    
    // Page pour prendre la présence
    public function take() {
        $this->authService->requireRole('teacher');
        
        $classId = $_GET['class_id'] ?? null;
        $date = $_GET['date'] ?? date('Y-m-d');
        
        if (!$classId) {
            header('Location: /teacher/attendance');
            exit;
        }
        
        // Vérifier que la classe appartient au professeur
        $class = $this->classRoomModel->findById($classId);
        if (!$class || $class['teacher_id'] != $_SESSION['user_id']) {
            header('Location: /teacher/attendance');
            exit;
        }
        
        // Récupérer les étudiants de la classe
        $students = $this->classRoomModel->getByClassroom($classId);
        
        // Vérifier si la présence a déjà été prise
        $existingAttendance = $this->attendanceModel->getAttendance($classId, $date);
        $attendanceTaken = !empty($existingAttendance);
        
        include __DIR__ . '/../Views/teacher/take_attendance.php';
    }
    
    // Sauvegarder la présence
    public function save() {
        $this->authService->requireRole('teacher');
        
        $classId = $_POST['class_id'] ?? null;
        $date = $_POST['date'] ?? null;
        $attendance = $_POST['attendance'] ?? [];
        
        if (!$classId || !$date) {
            $_SESSION['error'] = "Paramètres manquants";
            header('Location: /teacher/attendance');
            exit;
        }
        
        // Vérifier que la classe appartient au professeur
        $class = $this->classRoomModel->findById($classId);
        if (!$class || $class['teacher_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = "Classe non trouvée ou accès non autorisé";
            header('Location: /teacher/attendance');
            exit;
        }
        
        // Sauvegarder la présence
        if ($this->attendanceModel->takeAttendance($classId, $date, $attendance)) {
            $_SESSION['success'] = "Présence enregistrée avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de l'enregistrement";
        }
        
        header("Location: /teacher/attendance/take?class_id=$classId&date=$date");
        exit;
    }
    
    // Voir les statistiques
    public function stats() {
        $this->authService->requireRole('teacher');
        
        $classId = $_GET['class_id'] ?? null;
        
        if (!$classId) {
            header('Location: /teacher/attendance');
            exit;
        }
        
        // Vérifier que la classe appartient au professeur
        $class = $this->classRoomModel->findById($classId);
        if (!$class || $class['teacher_id'] != $_SESSION['user_id']) {
            header('Location: /teacher/attendance');
            exit;
        }
        
        // Récupérer les statistiques
        $generalStats = $this->attendanceModel->getAttendanceStats($classId);
        $studentStats = $this->attendanceModel->getStudentAttendanceStats($classId);
        $attendanceDates = $this->attendanceModel->getAttendanceDates($classId);
        
        include __DIR__ . '/../Views/teacher/attendance_stats.php';
    }
}
