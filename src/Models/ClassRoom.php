<?php
namespace App\Models;
class ClassRoom {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }
    
   public function create($name, $description, $teacher_id) {
        $stmt = $this->db->prepare("INSERT INTO classrooms (name, description, teacher_id) VALUES (?, ?, ?)");
        return $stmt->execute([$name, $description, $teacher_id]);
    }
    public function findByTeacherId($teacher_id) {
        $stmt = $this->db->prepare("SELECT * FROM classrooms WHERE teacher_id = ?");
        $stmt->execute([$teacher_id]);
        return $stmt->fetchAll();
    }
    
    // Récupérer les classes d'un étudiant
    public function findByStudentId($student_id) {
        $stmt = $this->db->prepare(
            "SELECT c.* 
             FROM classrooms c
             JOIN classroom_students cs ON c.id = cs.classroom_id
             WHERE cs.student_id = ?"
        );
        $stmt->execute([$student_id]);
        return $stmt->fetchAll();
    }
    

}
?>