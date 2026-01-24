<?php
namespace App\Models;

class Chat {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }
    
    // Envoyer un message
    public function sendMessage($classroom_id, $user_id, $message) {
        $stmt = $this->db->prepare(
            "INSERT INTO chat_messages (classroom_id, user_id, message, created_at) 
             VALUES (?, ?, ?, NOW())"
        );
        return $stmt->execute([$classroom_id, $user_id, $message]);
    }
    
    // Récupérer les messages récents d'une classe
    public function getRecentMessages($classroom_id, $limit = 50) {
        $stmt = $this->db->prepare(
            "SELECT cm.*, u.name, u.role
             FROM chat_messages cm
             JOIN users u ON cm.user_id = u.id
             WHERE cm.classroom_id = ?
             ORDER BY cm.created_at DESC
             LIMIT " . (int)$limit
        );
        $stmt->execute([$classroom_id]);
        $messages = $stmt->fetchAll();
        
        // Inverser pour avoir les messages dans l'ordre chronologique
        return array_reverse($messages);
    }
    
    // Récupérer les classes d'un étudiant pour le chat
    public function getStudentClasses($student_id) {
        $stmt = $this->db->prepare(
            "SELECT c.* 
             FROM classrooms c
             JOIN classroom_students cs ON c.id = cs.classroom_id
             WHERE cs.student_id = ?"
        );
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }
    
    // Récupérer les classes d'un enseignant pour le chat
    public function getTeacherClasses($teacher_id) {
        $stmt = $this->db->prepare(
            "SELECT * FROM classrooms WHERE teacher_id = ?"
        );
        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll();
    }
    
    // Vérifier si un utilisateur peut accéder au chat d'une classe
    public function canAccessChat($classroom_id, $user_id, $user_role) {
        if ($user_role === 'teacher') {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) FROM classrooms WHERE id = ? AND teacher_id = ?"
            );
            $stmt->execute([$classroom_id, $user_id]);
            return $stmt->fetchColumn() > 0;
        } else {
            $stmt = $this->db->prepare(
                "SELECT COUNT(*) 
                 FROM classroom_students cs
                 WHERE cs.classroom_id = ? AND cs.student_id = ?"
            );
            $stmt->execute([$classroom_id, $user_id]);
            return $stmt->fetchColumn() > 0;
        }
    }
}
