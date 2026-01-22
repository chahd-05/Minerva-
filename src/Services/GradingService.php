<?php
namespace App\Services;

use App\Models\WorkAssignment;

class GradingService {
    private $assignmentModel;
    
    public function __construct() {
        $this->assignmentModel = new WorkAssignment();
    }
    
    public function gradeSubmission($submission_id, $score, $comment = null) {
        if (!is_numeric($score) || $score < 0 || $score > 20) {
            throw new \Exception("La note doit être un nombre entre 0 et 20");
        }
        
        if ($this->assignmentModel->hasGrade($submission_id)) {
            $this->assignmentModel->updateGrade($submission_id, $score, $comment);
            return "Note mise à jour avec succès";
        } else {
            $this->assignmentModel->addGrade($submission_id, $score, $comment);
            return "Note ajoutée avec succès";
        }
    }
}
