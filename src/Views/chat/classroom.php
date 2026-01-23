<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - <?= htmlspecialchars($classroom['name']) ?> - Minerva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
            color: #667eea !important;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .role-badge {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        .chat-container {
            height: calc(100vh - 76px);
            display: flex;
            flex-direction: column;
        }
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
            background: #f8f9fa;
        }
        .message {
            margin-bottom: 1rem;
            max-width: 70%;
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .message-own {
            margin-left: auto;
        }
        .message-bubble {
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            position: relative;
            word-wrap: break-word;
        }
        .message-own .message-bubble {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }
        .message-other .message-bubble {
            background: white;
            color: #333;
            border: 1px solid #e9ecef;
        }
        .message-author {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #495057;
        }
        .message-time {
            font-size: 0.75rem;
            opacity: 0.7;
            margin-top: 0.25rem;
        }
        .message-own .message-time {
            color: rgba(255, 255, 255, 0.8);
        }
        .teacher-badge {
            background: #ffc107;
            color: #333;
            padding: 0.125rem 0.375rem;
            border-radius: 0.5rem;
            font-size: 0.625rem;
            margin-left: 0.5rem;
            font-weight: 500;
        }
        .input-container {
            padding: 1rem;
            background: white;
            border-top: 1px solid #e9ecef;
        }
        .message-input {
            border-radius: 2rem;
            border: 1px solid #dee2e6;
            padding: 0.75rem 1.25rem;
            resize: none;
        }
        .message-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .send-button {
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            border: none;
            color: white;
            transition: transform 0.2s;
        }
        .send-button:hover:not(:disabled) {
            transform: scale(1.05);
        }
        .send-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .typing-indicator {
            display: none;
            padding: 0.5rem 1rem;
            color: #6c757d;
            font-style: italic;
            font-size: 0.875rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
        .chat-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
        }
        .online-indicator {
            width: 8px;
            height: 8px;
            background: #28a745;
            border-radius: 50%;
            display: inline-block;
            margin-left: 0.5rem;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-graduation-cap me-2"></i>
                Minerva
            </a>
            <div class="navbar-nav ms-auto">
                <div class="user-info">
                    <span class="badge bg-primary role-badge"><?php echo htmlspecialchars($userRole); ?></span>
                    <span><?php echo htmlspecialchars($userName); ?></span>
                    <a href="/logout" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="chat-container">
        <div class="chat-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="fas fa-comments me-2"></i>
                        <?= htmlspecialchars($classroom['name']) ?>
                    </h4>
                    <small>
                        <i class="fas fa-circle online-indicator"></i>
                        Chat de classe actif
                    </small>
                </div>
                <a href="/chat" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour aux classes
                </a>
            </div>
        </div>

        <div class="messages-container" id="messages">
            <?php if ($messages): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="message <?= $message['user_id'] == $_SESSION['user_id'] ? 'message-own' : 'message-other' ?>">
                        <?php if ($message['user_id'] != $_SESSION['user_id']): ?>
                            <div class="message-author">
                                <?= htmlspecialchars($message['name']) ?>
                                <?php if ($message['role'] === 'teacher'): ?>
                                    <span class="teacher-badge">Prof</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <div class="message-bubble">
                            <?= htmlspecialchars($message['message']) ?>
                        </div>
                        <div class="message-time">
                            <?= date('H:i', strtotime($message['created_at'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-comments"></i>
                    <h5 class="mt-3">Aucun message pour le moment</h5>
                    <p class="text-muted">Soyez le premier à saluer la classe ! 👋</p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="typing-indicator" id="typingIndicator">
            Quelqu'un est en train d'écrire...
        </div>

        <div class="input-container">
            <div class="input-group">
                <textarea class="form-control message-input" id="messageInput" 
                          placeholder="Tapez votre message..." 
                          maxlength="500" 
                          rows="1"></textarea>
                <button class="btn send-button" id="sendButton" type="button">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
            <small class="text-muted mt-2 d-block">
                <i class="fas fa-info-circle me-1"></i>
                Appuyez sur Entrée pour envoyer, Shift+Entrée pour sauter une ligne
            </small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const classId = <?= $classroom['id'] ?>;
        const userId = <?= $_SESSION['user_id'] ?>;
        let isRefreshing = false;
        let lastMessageCount = 0;

        // Fonction pour scroller en bas
        function scrollToBottom() {
            const container = document.getElementById('messages');
            container.scrollTop = container.scrollHeight;
        }

        // Fonction pour rafraîchir les messages
        async function refreshMessages() {
            if (isRefreshing) return;
            isRefreshing = true;

            try {
                const response = await fetch(`/chat/refresh?class_id=${classId}`);
                const data = await response.json();
                
                if (data.success) {
                    const container = document.getElementById('messages');
                    const currentScrollTop = container.scrollTop;
                    const isAtBottom = container.scrollHeight - container.clientHeight <= currentScrollTop + 50;
                    
                    // Reconstruire les messages
                    let html = '';
                    if (data.messages.length === 0) {
                        html = `
                            <div class="empty-state">
                                <i class="fas fa-comments"></i>
                                <h5 class="mt-3">Aucun message pour le moment</h5>
                                <p class="text-muted">Soyez le premier à saluer la classe ! 👋</p>
                            </div>
                        `;
                    } else {
                        data.messages.forEach(msg => {
                            const isOwn = msg.user_id == userId;
                            html += `
                                <div class="message ${isOwn ? 'message-own' : 'message-other'}">
                                    ${!isOwn ? `
                                        <div class="message-author">
                                            ${msg.name}
                                            ${msg.role === 'teacher' ? '<span class="teacher-badge">Prof</span>' : ''}
                                        </div>
                                    ` : ''}
                                    <div class="message-bubble">${msg.message}</div>
                                    <div class="message-time">${new Date(msg.created_at).toLocaleTimeString('fr-FR', {hour: '2-digit', minute:'2-digit'})}</div>
                                </div>
                            `;
                        });
                    }
                    
                    container.innerHTML = html;
                    
                    // Garder la position si l'utilisateur n'est pas en bas
                    if (isAtBottom || data.messages.length !== lastMessageCount) {
                        scrollToBottom();
                    }
                    
                    lastMessageCount = data.messages.length;
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
            
            isRefreshing = false;
        }

        // Fonction pour envoyer un message
        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const button = document.getElementById('sendButton');
            const message = input.value.trim();
            
            if (!message) return;
            
            // Désactiver le bouton
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            try {
                const formData = new FormData();
                formData.append('class_id', classId);
                formData.append('message', message);
                
                const response = await fetch('/chat/send', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    input.value = '';
                    await refreshMessages();
                    scrollToBottom();
                } else {
                    alert('Erreur: ' + data.error);
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'envoi du message');
            }
            
            // Réactiver le bouton
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-paper-plane"></i>';
        }

        // Auto-resize textarea
        function autoResize() {
            const textarea = document.getElementById('messageInput');
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
        }

        // Écouteurs d'événements
        document.getElementById('sendButton').addEventListener('click', sendMessage);
        
        document.getElementById('messageInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
            autoResize();
        });

        document.getElementById('messageInput').addEventListener('input', autoResize);

        // Rafraîchissement automatique toutes les 3 secondes
        setInterval(refreshMessages, 3000);

        // Scroll en bas au chargement
        scrollToBottom();

        // Focus sur l'input
        document.getElementById('messageInput').focus();

        // Initialiser le compteur de messages
        lastMessageCount = <?= count($messages) ?>;
    </script>
</body>
</html>
