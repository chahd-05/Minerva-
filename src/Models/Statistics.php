<?php
namespace App\Models;

class Statistics {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }
    
    // Statistiques simples pour un professeur
    public function getTeacherStats($teacher_id) {
        $stmt = $this->db->prepare(
            "SELECT 
                COUNT(DISTINCT c.id) as total_classes,
                COUNT(DISTINCT cs.student_id) as total_students,
                COUNT(DISTINCT a.date) as attendance_days
             FROM classrooms c
             LEFT JOIN classroom_students cs ON c.id = cs.classroom_id
             LEFT JOIN attendance a ON c.id = a.classroom_id
             WHERE c.teacher_id = ?"
        );
        $stmt->execute([$teacher_id]);
        return $stmt->fetch();
    }
    
    // Statistiques de présence pour une classe
    public function getClassAttendanceStats($class_id) {
        $stmt = $this->db->prepare(
            "SELECT 
                COUNT(*) as total_sessions,
                SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN a.status = 0 THEN 1 ELSE 0 END) as absent_count,
                ROUND(AVG(CASE WHEN a.status = 1 THEN 100 ELSE 0 END), 1) as attendance_rate
             FROM attendance a
             WHERE a.classroom_id = ?"
        );
        $stmt->execute([$class_id]);
        return $stmt->fetch();
    }
    
    // Performance simple par étudiant
    public function getStudentPerformance($class_id) {
        $stmt = $this->db->prepare(
            "SELECT 
                u.id,
                u.name,
                u.email,
                COUNT(DISTINCT a.id) as attendance_sessions,
                SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) as attendance_present,
                ROUND(SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(DISTINCT a.id), 1) as attendance_rate
             FROM users u
             JOIN classroom_students cs ON u.id = cs.student_id
             LEFT JOIN attendance a ON u.id = a.student_id AND a.classroom_id = ?
             WHERE cs.classroom_id = ?
             GROUP BY u.id, u.name, u.email
             ORDER BY attendance_rate DESC"
        );
        $stmt->execute([$class_id, $class_id]);
        return $stmt->fetchAll();
    }
    
    // Évolution simple des présences
    public function getMonthlyAttendance($class_id, $months = 6) {
        $stmt = $this->db->prepare(
            "SELECT 
                DATE_FORMAT(a.date, '%Y-%m') as month,
                COUNT(*) as sessions,
                SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) as present,
                ROUND(SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as rate
             FROM attendance a
             WHERE a.classroom_id = ?
             AND a.date >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
             GROUP BY DATE_FORMAT(a.date, '%Y-%m')
             ORDER BY month DESC
             LIMIT ?"
        );
        $stmt->execute([$class_id, $months, $months]);
        return $stmt->fetchAll();
    }
}
