<?php
namespace App\Models;

class Work {
    public $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }
    
  
    public function create($classroom_id, $title, $description, $deadline) {
        $stmt = $this->db->prepare("INSERT INTO works (classroom_id, title, description, deadline, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$classroom_id, $title, $description, $deadline]);
        return $this->db->lastInsertId();
    }
}
?>