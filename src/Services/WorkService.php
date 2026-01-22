<?php
namespace App\Services;

use App\Models\Work;

class WorkService {
    private $workModel;

    public function __construct() {
        $this->workModel = new Work();
    }

    public function create($classroom_id, $title, $description, $deadline, $file) {
        // 1. Validation métier
        if (empty($title) || empty($classroom_id)) {
            throw new \Exception("Champs obligatoires manquants");
        }
        // 2. Gestion du fichier (optionnel)
        $filePath = null;

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $extension;

            $uploadDir = __DIR__ . '/../../public/uploads/';
            move_uploaded_file($file['tmp_name'], $uploadDir . $fileName);

            $filePath = 'uploads/' . $fileName;
        }

        // 3. Appel du Model
        return $this->workModel->create(
            $classroom_id,
            $title,
            $description,
            $deadline,
            $filePath
        );
    }
}
