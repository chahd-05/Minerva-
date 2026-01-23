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
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM classrooms WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
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
    
    // Assigner un étudiant à une classe
    public function assignStudent($classroom_id, $student_id) {
        $stmt = $this->db->prepare(
            "INSERT INTO classroom_students (classroom_id, student_id) VALUES (?, ?)"
        );
        return $stmt->execute([$classroom_id, $student_id]);
    }
    
    // Retirer un étudiant d'une classe
    public function removeStudent($classroom_id, $student_id) {
        $stmt = $this->db->prepare(
            "DELETE FROM classroom_students WHERE classroom_id = ? AND student_id = ?"
        );
        return $stmt->execute([$classroom_id, $student_id]);
    }
    
    // Vérifier si un étudiant est déjà assigné à une classe
    public function isStudentAssigned($classroom_id, $student_id) {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM classroom_students WHERE classroom_id = ? AND student_id = ?"
        );
        $stmt->execute([$classroom_id, $student_id]);
        return $stmt->fetchColumn() > 0;
    }
    
    // Récupérer les étudiants non assignés à une classe
    public function getUnassignedStudents($teacher_id, $classroom_id) {
        $stmt = $this->db->prepare(
            "SELECT u.* 
             FROM users u
             LEFT JOIN classroom_students cs ON u.id = cs.student_id AND cs.classroom_id = ?
             WHERE u.role = 'student' 
             AND cs.student_id IS NULL
             AND u.id NOT IN (
                 SELECT student_id 
                 FROM classroom_students 
                 WHERE classroom_id = ?
             )"
        );
        $stmt->execute([$classroom_id, $classroom_id]);
        return $stmt->fetchAll();
    }
    
    // Récupérer les étudiants d'une classe
    public function getStudentsByClass($class_id) {
        $stmt = $this->db->prepare(
            "SELECT u.id, u.name, u.email
             FROM users u
             JOIN classroom_students cs ON u.id = cs.student_id
             WHERE cs.classroom_id = ? AND u.role = 'student'
             ORDER BY u.name"
        );
        $stmt->execute([$class_id]);
        return $stmt->fetchAll();
    }
    
    // Récupérer l'enseignant d'une classe
    public function getTeacherByClass($class_id) {
        $stmt = $this->db->prepare(
            "SELECT u.id, u.name, u.email
             FROM users u
             JOIN classrooms c ON u.id = c.teacher_id
             WHERE c.id = ? AND u.role = 'teacher'"
        );
        $stmt->execute([$class_id]);
        return $stmt->fetch();
    }
    

}
?>