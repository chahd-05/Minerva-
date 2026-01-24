<?php
namespace App\Models;

class Submission {
    private $db;

    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }

    // Travaux assignés à un étudiant
    public function getAssignedWorks($student_id) {
        $stmt = $this->db->prepare(
            "SELECT w.*, c.name as classroom_name, s.id as submission_id, s.submitted_at, s.content
             FROM works w
             JOIN work_assignments wa ON w.id = wa.work_id
             JOIN classrooms c ON w.classroom_id = c.id
             LEFT JOIN submissions s ON w.id = s.work_id AND wa.student_id = s.student_id
             WHERE wa.student_id = ?
             ORDER BY w.deadline ASC"
        );
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }

    // Vérifier si déjà soumis
    public function alreadySubmitted($work_id, $student_id) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM submissions
             WHERE work_id = ? AND student_id = ?"
        );
        $stmt->execute([$work_id, $student_id]);
        return $stmt->fetchColumn() > 0;
    }

    // Enregistrer une réponse (sans fichier pour l'instant)
    public function submit($work_id, $student_id, $content, $file_path = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO submissions (work_id, student_id, content)
             VALUES (?, ?, ?)"
        );
        return $stmt->execute([$work_id, $student_id, $content]);
    }
    
    // Récupérer les détails d'une soumission
    public function getSubmission($submission_id) {
        $stmt = $this->db->prepare(
            "SELECT s.*, w.title, u.name as student_name
             FROM submissions s
             JOIN works w ON s.work_id = w.id
             JOIN users u ON s.student_id = u.id
             WHERE s.id = ?"
        );
        $stmt->execute([$submission_id]);
        return $stmt->fetch();
    }
}
