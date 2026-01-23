<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Models\Statistics;
use App\Models\ClassRoom;

class StatisticsController {
    private $authService;
    private $statisticsModel;
    private $classRoomModel;
    
    public function __construct() {
        $this->authService = new AuthService();
        $this->statisticsModel = new Statistics();
        $this->classRoomModel = new ClassRoom();
    }
    
    // Page principale des statistiques
    public function index() {
        $this->authService->requireRole('teacher');
        
        $teacherId = $_SESSION['user_id'];
        
        // Statistiques simples du professeur
        $teacherStats = $this->statisticsModel->getTeacherStats($teacherId);
        
        // Classes du professeur
        $classes = $this->classRoomModel->findByTeacherId($teacherId);
        
        include __DIR__ . '/../Views/teacher/statistics_index.php';
    }
    
    // Statistiques simples pour une classe
    public function classStats() {
        $this->authService->requireRole('teacher');
        
        $classId = $_GET['class_id'] ?? null;
        
        if (!$classId) {
            header('Location: /teacher/statistics');
            exit;
        }
        
        // Vérifier que la classe appartient au professeur
        $class = $this->classRoomModel->findById($classId);
        if (!$class || $class['teacher_id'] != $_SESSION['user_id']) {
            header('Location: /teacher/statistics');
            exit;
        }
        
        // Statistiques simples pour la classe
        $attendanceStats = $this->statisticsModel->getClassAttendanceStats($classId);
        $studentPerformance = $this->statisticsModel->getStudentPerformance($classId);
        $monthlyAttendance = $this->statisticsModel->getMonthlyAttendance($classId);
        
        include __DIR__ . '/../Views/teacher/class_statistics.php';
    }
}
