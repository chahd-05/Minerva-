<?php
namespace App\Models;

class WorkAssignment {
    private $db;

    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }

    public function assignToStudent($work_id, $student_id) {
        // Vérifier si déjà assigné
        if ($this->isAlreadyAssigned($work_id, $student_id)) {
            return true; // Déjà assigné, pas d'erreur
        }
        
        $stmt = $this->db->prepare(
            "INSERT INTO work_assignments (work_id, student_id)
             VALUES (?, ?)"
        );
        return $stmt->execute([$work_id, $student_id]);
    }
    
    // Retirer l'assignment d'un travail à un étudiant
    public function removeAssignment($work_id, $student_id) {
        $stmt = $this->db->prepare(
            "DELETE FROM work_assignments WHERE work_id = ? AND student_id = ?"
        );
        return $stmt->execute([$work_id, $student_id]);
    }

    public function isAlreadyAssigned($work_id, $student_id) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) 
             FROM work_assignments 
             WHERE work_id = ? AND student_id = ?"
        );
        $stmt->execute([$work_id, $student_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function getStudentAssignedWorks($student_id) {
        $stmt = $this->db->prepare(
            "SELECT w.id, w.title, w.description, w.deadline, c.name as classroom_name,
                    s.id as submission_id, s.submitted_at, s.content,
                    g.score, g.comment as grade_comment
             FROM work_assignments wa
             JOIN works w ON wa.work_id = w.id
             JOIN classrooms c ON w.classroom_id = c.id
             LEFT JOIN submissions s ON wa.work_id = s.work_id AND wa.student_id = s.student_id
             LEFT JOIN grades g ON s.id = g.submission_id
             WHERE wa.student_id = ?
             ORDER BY w.deadline ASC"
        );
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }

    public function submitWork($work_id, $student_id, $content) {
        $stmt = $this->db->prepare(
            "INSERT INTO submissions (work_id, student_id, content, submitted_at)
             VALUES (?, ?, ?, NOW())"
        );
        return $stmt->execute([$work_id, $student_id, $content]);
    }

    public function hasSubmitted($work_id, $student_id) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM submissions WHERE work_id = ? AND student_id = ?"
        );
        $stmt->execute([$work_id, $student_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function getSubmissionsForTeacher($teacher_id) {
        $stmt = $this->db->prepare(
            "SELECT s.*, w.title as work_title, u.name as student_name, c.name as classroom_name,
                    g.score, g.comment as grade_comment
             FROM submissions s
             JOIN works w ON s.work_id = w.id
             JOIN users u ON s.student_id = u.id
             JOIN classrooms c ON w.classroom_id = c.id
             LEFT JOIN grades g ON s.id = g.submission_id
             WHERE c.teacher_id = ?
             ORDER BY s.submitted_at DESC"
        );
        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll();
    }

    public function addGrade($submission_id, $score, $comment = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO grades (submission_id, score, comment) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$submission_id, $score, $comment]);
    }

    public function updateGrade($submission_id, $score, $comment = null) {
        $stmt = $this->db->prepare(
            "UPDATE grades SET score = ?, comment = ? WHERE submission_id = ?"
        );
        return $stmt->execute([$score, $comment, $submission_id]);
    }

    public function hasGrade($submission_id) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM grades WHERE submission_id = ?"
        );
        $stmt->execute([$submission_id]);
        return $stmt->fetchColumn() > 0;
    }

    // Récupérer les étudiants d'une classe avec leur statut d'assignment
    public function getStudentsByClass($class_id, $work_id = null) {
        if ($work_id) {
            $stmt = $this->db->prepare(
                "SELECT u.id, u.name, 
                        CASE WHEN wa.student_id IS NOT NULL THEN 1 ELSE 0 END as is_assigned
                 FROM users u
                 JOIN classroom_students cs ON u.id = cs.student_id
                 LEFT JOIN work_assignments wa ON u.id = wa.student_id AND wa.work_id = ?
                 WHERE cs.classroom_id = ? AND u.role = 'student'"
            );
            $stmt->execute([$work_id, $class_id]);
        } else {
            $stmt = $this->db->prepare(
                "SELECT u.id, u.name, 0 as is_assigned
                 FROM users u
                 JOIN classroom_students cs ON u.id = cs.student_id
                 WHERE cs.classroom_id = ? AND u.role = 'student'"
            );
            $stmt->execute([$class_id]);
        }
        return $stmt->fetchAll();
    }

    // Récupérer les travaux d'une classe
    public function getWorksByClass($class_id) {
        $stmt = $this->db->prepare(
            "SELECT id, title FROM works WHERE classroom_id = ? ORDER BY created_at DESC"
        );
        $stmt->execute([$class_id]);
        return $stmt->fetchAll();
    }
}
