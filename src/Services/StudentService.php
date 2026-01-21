<?php
namespace App\Services;

use App\Models\User;
use App\Services\MailerService;

class StudentService {
    private $userModel;
    private $mailer;

    public function __construct() {
        $this->userModel = new User();
        $this->mailer = new MailerService();
    }

    public function createStudent($name, $email, $classId) {
        // 1️⃣ Generate random password
        $password = bin2hex(random_bytes(4)); // 8 characters

        // 2️⃣ Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // 3️⃣ Save student in DB
        $studentId = $this->userModel->createWithArray([
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'role' => 'student'
        ]);

        // 4️⃣ Assign to class
        $this->userModel->assignToClass($studentId, $classId);

        // 5️⃣ Send email to student
        $subject = "Bienvenue sur Minerva";
        $body = "
            Bonjour $name,<br><br>
            Votre compte étudiant a été créé.<br>
            Vous pouvez vous connecter avec les identifiants suivants : <br>
            <br>
            Email: $email<br>
            Mot de passe: $password<br>
            <br>
          
        ";

        $this->mailer->send($email, $subject, $body);

        return $studentId;
    }
}
