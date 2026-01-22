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
}
