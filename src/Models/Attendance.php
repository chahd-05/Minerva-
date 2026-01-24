<?php
namespace App\Models;

class Attendance {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }
    
    // Prendre la présence pour une classe et une date
    public function takeAttendance($classroom_id, $date, $attendance_data) {
        // D'abord supprimer les anciennes présences pour cette date et classe
        $this->deleteAttendance($classroom_id, $date);
        
        // Insérer les nouvelles présences
        $stmt = $this->db->prepare(
            "INSERT INTO attendance (classroom_id, student_id, date, status) VALUES (?, ?, ?, ?)"
        );
        
        foreach ($attendance_data as $student_id => $status) {
            // Convertir 'present'/'absent' en 1/0
            $statusValue = ($status === 'present') ? 1 : 0;
            $stmt->execute([$classroom_id, $student_id, $date, $statusValue]);
        }
        
        return true;
    }
    
    // Supprimer la présence pour une classe et une date
    public function deleteAttendance($classroom_id, $date) {
        $stmt = $this->db->prepare(
            "DELETE FROM attendance WHERE classroom_id = ? AND date = ?"
        );
        return $stmt->execute([$classroom_id, $date]);
    }
    
    // Récupérer la présence pour une classe et une date
    public function getAttendance($classroom_id, $date) {
        $stmt = $this->db->prepare(
            "SELECT a.*, u.name, u.email 
             FROM attendance a
             JOIN users u ON a.student_id = u.id
             WHERE a.classroom_id = ? AND a.date = ?"
        );
        $stmt->execute([$classroom_id, $date]);
        return $stmt->fetchAll();
    }
    
    // Récupérer les statistiques de présence pour une classe
    public function getAttendanceStats($classroom_id) {
        $stmt = $this->db->prepare(
            "SELECT 
                COUNT(*) as total_sessions,
                SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_present,
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as total_absent,
                COUNT(DISTINCT date) as session_count
             FROM attendance 
             WHERE classroom_id = ?"
        );
        $stmt->execute([$classroom_id]);
        return $stmt->fetch();
    }
    
    // Récupérer les statistiques par étudiant
    public function getStudentAttendanceStats($classroom_id) {
        $stmt = $this->db->prepare(
            "SELECT 
                u.id,
                u.name,
                u.email,
                COUNT(*) as total_sessions,
                SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) as present_count,
                SUM(CASE WHEN a.status = 0 THEN 1 ELSE 0 END) as absent_count,
                ROUND((SUM(CASE WHEN a.status = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 2) as attendance_rate
             FROM users u
             JOIN classroom_students cs ON u.id = cs.student_id
             LEFT JOIN attendance a ON u.id = a.student_id AND a.classroom_id = ?
             WHERE cs.classroom_id = ?
             GROUP BY u.id, u.name, u.email
             ORDER BY u.name"
        );
        $stmt->execute([$classroom_id, $classroom_id]);
        return $stmt->fetchAll();
    }
    
    // Vérifier si la présence a déjà été prise pour une classe et une date
    public function isAttendanceTaken($classroom_id, $date) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM attendance WHERE classroom_id = ? AND date = ?"
        );
        $stmt->execute([$classroom_id, $date]);
        return $stmt->fetchColumn() > 0;
    }
    
    // Récupérer les dates de présence pour une classe
    public function getAttendanceDates($classroom_id) {
        $stmt = $this->db->prepare(
            "SELECT DISTINCT date FROM attendance WHERE classroom_id = ? ORDER BY date DESC"
        );
        $stmt->execute([$classroom_id]);
        return $stmt->fetchAll();
    }
}
