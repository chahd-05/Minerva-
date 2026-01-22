<?php
namespace App\Models;

class Grade {
    private $db;

    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }

    public function gradeSubmission($submission_id, $score, $comment) {
        $stmt = $this->db->prepare(
            "INSERT INTO grades (submission_id, score, comment)
             VALUES (?, ?, ?)"
        );
        return $stmt->execute([$submission_id, $score, $comment]);
    }

    public function getSubmissions() {
        $stmt = $this->db->query(
            "SELECT s.id, s.content, u.name
             FROM submissions s
             JOIN users u ON s.student_id = u.id
             LEFT JOIN grades g ON s.id = g.submission_id
             WHERE g.submission_id IS NULL"
        );
        return $stmt->fetchAll();
    }

    public function getTeacherAssignedWorks($teacher_id) {
        $stmt = $this->db->prepare(
            "SELECT DISTINCT w.id, w.title, w.description, w.deadline, c.name as classroom_name,
                    COUNT(wa.student_id) as assigned_students
             FROM works w
             JOIN classrooms c ON w.classroom_id = c.id
             JOIN work_assignments wa ON w.id = wa.work_id
             WHERE c.teacher_id = ?
             GROUP BY w.id
             ORDER BY w.deadline DESC"
        );
        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll();
    }
    
    public function getStudentGrades($student_id) {
        $stmt = $this->db->prepare(
            "SELECT g.score, g.comment, w.title as work_title, c.name as classroom_name, s.submitted_at
             FROM grades g
             JOIN submissions s ON g.submission_id = s.id
             JOIN works w ON s.work_id = w.id
             JOIN classrooms c ON w.classroom_id = c.id
             WHERE s.student_id = ?
             ORDER BY s.submitted_at DESC"
        );
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }
}
