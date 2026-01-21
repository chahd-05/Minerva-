<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService {
    private $mail;

    public function __construct() {
        $this->mail = new PHPMailer(true);
        $this->setup();
    }

    private function setup() {
        $this->mail->isSMTP();
        $this->mail->Host = $_ENV['MAIL_HOST'];
        $this->mail->SMTPAuth = true;
        $this->mail->Username = $_ENV['MAIL_USERNAME'];
        $this->mail->Password = $_ENV['MAIL_PASSWORD'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port = $_ENV['MAIL_PORT'];
        $this->mail->setFrom($_ENV['MAIL_USERNAME'], $_ENV['MAIL_FROM_NAME']);
        $this->mail->isHTML(true);
    }

    public function send($toEmail, $subject, $body) {
        try {
            $this->mail->addAddress($toEmail);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail error: " . $e->getMessage());
            return false;
        }
    }
}
