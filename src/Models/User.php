<?php
namespace App\Models;

class User {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getPDO();
    }
    
    public function create($name, $email, $password, $role ) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role, created_at) VALUES (?,?, ?, ?, NOW())");
        return $stmt->execute([$name, $email, $hashedPassword,$role]);
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
