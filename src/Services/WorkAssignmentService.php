<?php
namespace App\Services;

use App\Models\WorkAssignment;
use App\Models\Work;

class WorkAssignmentService {
    private $assignmentModel;
    private $workModel;

    public function __construct() {
        $this->assignmentModel = new WorkAssignment();
        $this->workModel = new Work();
    }

    public function assignWork($work_id, $student_ids) {
        if (!$work_id || empty($student_ids)) {
            throw new \Exception("Travail ou étudiants manquants");
        }

        if (!$this->workModel->findById($work_id)) {
            throw new \Exception("Travail inexistant");
        }

        $count = 0;

        foreach ($student_ids as $student_id) {
            if (!$this->assignmentModel->isAlreadyAssigned($work_id, $student_id)) {
                $this->assignmentModel->assignToStudent($work_id, $student_id);
                $count++;
            }
        }

        if ($count === 0) {
            throw new \Exception("Travail déjà assigné à ces étudiants");
        }

        return "$count étudiant(s) assigné(s)";
    }
}
