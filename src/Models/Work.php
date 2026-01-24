<?php
namespace App\Models;

class Work {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }
    
    public function create($classroom_id, $title, $description, $deadline, $filePath = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO works (classroom_id, title, description, deadline, file_path, created_at)
             VALUES (?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([
            $classroom_id,
            $title,
            $description,
            $deadline,
            $filePath
        ]);
        
        return $this->db->lastInsertId();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM works WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getTeacherAssignedWorks($teacher_id) {
        $stmt = $this->db->prepare(
            "SELECT w.*, c.name as classroom_name,
                    COUNT(DISTINCT wa.student_id) as assigned_students
             FROM works w
             JOIN classrooms c ON w.classroom_id = c.id
             LEFT JOIN work_assignments wa ON w.id = wa.work_id
             WHERE c.teacher_id = ?
             GROUP BY w.id, c.name
             ORDER BY w.created_at DESC"
        );
        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll();
    }
}
