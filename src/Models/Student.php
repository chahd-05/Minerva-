<?php
namespace App\Models;

class Student {
    private $db;

    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }

    public function getByClassroom($classroom_id) {
        $stmt = $this->db->prepare(
            "SELECT u.*
             FROM users u
             JOIN classroom_students cs ON u.id = cs.student_id
             WHERE cs.classroom_id = ?"
        );
        $stmt->execute([$classroom_id]);
        return $stmt->fetchAll();
    }
}
