<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat de classe - Minerva</title>
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
        .chat-card {
            transition: transform 0.2s;
            height: 100%;
            cursor: pointer;
        }
        .chat-card:hover {
            transform: translateY(-2px);
        }
        .chat-card .card-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            font-weight: bold;
        }
        .chat-card .card-body {
            padding: 1.5rem;
        }
        .chat-card .card-text {
            color: #6c757d;
            margin-bottom: 1rem;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            color: #dee2e6;
        }
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .welcome-card .card-text {
            color: rgba(255, 255, 255, 0.9);
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

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>
                        <i class="fas fa-comments me-2"></i>
                        Chat de classe
                    </h2>
                    <a href="/<?= $userRole === 'teacher' ? 'teacher' : 'student' ?>/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au tableau de bord
                    </a>
                </div>

                <!-- Carte de bienvenue -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card welcome-card">
                            <div class="card-body text-center">
                                <h3 class="mb-3">
                                    <i class="fas fa-comments me-2"></i>
                                    Bienvenue dans le chat de classe
                                </h3>
                                <p class="card-text mb-0">
                                    Choisissez une classe ci-dessous pour commencer à discuter avec vos camarades et votre enseignant.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cartes des classes -->
                <div class="row">
                    <?php if ($classes): ?>
                        <?php foreach ($classes as $class): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card chat-card" onclick="window.location.href='/chat/classroom?class_id=<?= $class['id'] ?>'">
                                    <div class="card-header">
                                        <i class="fas fa-chalkboard me-2"></i>
                                        <?= htmlspecialchars($class['name']) ?>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">
                                            <?= htmlspecialchars($class['description'] ?? 'Pas de description') ?>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-users me-1"></i>
                                                <?= $userRole === 'teacher' ? 'Vos étudiants' : 'Votre classe' ?>
                                            </span>
                                            <span class="text-primary">
                                                <i class="fas fa-arrow-right"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body empty-state">
                                    <i class="fas fa-comments"></i>
                                    <h4 class="mb-3">Aucune classe disponible</h4>
                                    <p class="text-muted">
                                        <?php if ($userRole === 'teacher'): ?>
                                            Vous n'avez créé aucune classe pour le moment.
                                            <a href="/teacher/classrooms" class="btn btn-primary mt-3">
                                                <i class="fas fa-plus me-2"></i>
                                                Créer une classe
                                            </a>
                                        <?php else: ?>
                                            Vous n'êtes inscrit dans aucune classe pour le moment.
                                            Contactez votre enseignant pour être ajouté à une classe.
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Actions rapides -->
                <?php if ($classes): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <i class="fas fa-bolt me-2"></i>
                                    Actions rapides
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php if ($userRole === 'teacher'): ?>
                                            <div class="col-md-4 mb-3">
                                                <a href="/teacher/classrooms" class="btn btn-outline-primary w-100">
                                                    <i class="fas fa-chalkboard me-2"></i>
                                                    Gérer mes classes
                                                </a>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <a href="/teacher/attendance" class="btn btn-outline-success w-100">
                                                    <i class="fas fa-user-check me-2"></i>
                                                    Prendre la présence
                                                </a>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <a href="/teacher/create-work" class="btn btn-outline-info w-100">
                                                    <i class="fas fa-file-alt me-2"></i>
                                                    Créer un devoir
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <div class="col-md-4 mb-3">
                                                <a href="/student/courses" class="btn btn-outline-primary w-100">
                                                    <i class="fas fa-book me-2"></i>
                                                    Mes cours
                                                </a>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <a href="/student/my-class" class="btn btn-outline-info w-100">
                                                    <i class="fas fa-users me-2"></i>
                                                    Ma classe
                                                </a>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <a href="/student/submissions" class="btn btn-outline-success w-100">
                                                    <i class="fas fa-file-alt me-2"></i>
                                                    Mes devoirs
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add click animation to cards
        document.querySelectorAll('.chat-card').forEach(card => {
            card.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
</body>
</html>
