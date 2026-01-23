<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Models\Chat;

class ChatController {
    private $authService;
    private $chatModel;
    
    public function __construct() {
        $this->authService = new AuthService();
        $this->chatModel = new Chat();
    }
    
    // Page principale du chat
    public function index() {
        $this->authService->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];
        
        // Récupérer les classes selon le rôle
        if ($userRole === 'teacher') {
            $classes = $this->chatModel->getTeacherClasses($userId);
        } else {
            $classes = $this->chatModel->getStudentClasses($userId);
        }
        
        include __DIR__ . '/../Views/chat/index.php';
    }
    
    // Page de chat pour une classe spécifique
    public function classroom() {
        $this->authService->requireAuth();
        
        $classroomId = $_GET['class_id'] ?? null;
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];
        
        if (!$classroomId) {
            header('Location: /chat');
            exit;
        }
        
        // Vérifier si l'utilisateur peut accéder à ce chat
        if (!$this->chatModel->canAccessChat($classroomId, $userId, $userRole)) {
            header('Location: /chat');
            exit;
        }
        
        // Récupérer les messages récents
        $messages = $this->chatModel->getRecentMessages($classroomId);
        
        // Récupérer les informations de la classe
        $classModel = new \App\Models\ClassRoom();
        $classroom = $classModel->findById($classroomId);
        
        include __DIR__ . '/../Views/chat/classroom.php';
    }
    
    // Envoyer un message (AJAX)
    public function sendMessage() {
        $this->authService->requireAuth();
        
        $classroomId = $_POST['class_id'] ?? null;
        $message = trim($_POST['message'] ?? '');
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];
        
        if (!$classroomId || empty($message)) {
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            exit;
        }
        
        // Vérifier si l'utilisateur peut accéder à ce chat
        if (!$this->chatModel->canAccessChat($classroomId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
            exit;
        }
        
        // Envoyer le message
        if ($this->chatModel->sendMessage($classroomId, $userId, $message)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'envoi']);
        }
        exit;
    }
    
    // Rafraîchir les messages (AJAX)
    public function refreshMessages() {
        $this->authService->requireAuth();
        
        $classroomId = $_GET['class_id'] ?? null;
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];
        
        if (!$classroomId) {
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            exit;
        }
        
        // Vérifier si l'utilisateur peut accéder à ce chat
        if (!$this->chatModel->canAccessChat($classroomId, $userId, $userRole)) {
            echo json_encode(['success' => false, 'error' => 'Accès non autorisé']);
            exit;
        }
        
        // Récupérer les messages récents
        $messages = $this->chatModel->getRecentMessages($classroomId);
        
        echo json_encode(['success' => true, 'messages' => $messages]);
        exit;
    }
}
