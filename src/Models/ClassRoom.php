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
    

}
?>