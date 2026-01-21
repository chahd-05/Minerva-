<?php
namespace App\Services;
use App\Models\ClassRoom;
class ClassService {
    private $classModel;
    public function __construct() {
        $this->classModel = new ClassRoom();
    }
    public function create($name,$description,$teacher_id) {
        if (empty($name) || empty($description) ) {
            throw new \Exception("Veuillez remplir tous les champs");
        }
        $classrooms = $this->classModel->findByTeacherId($teacher_id);
        foreach ($classrooms as $classroom) {
            if ($classroom['name'] === $name) {
                throw new \Exception("Une classe avec ce nom existe deja");
            }
        }
        return $this->classModel->create($name,$description,$teacher_id);
    }
    public function findByTeacherId($teacher_id) {
        return $this->classModel->findByTeacherId($teacher_id);
    }


}