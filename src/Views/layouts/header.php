<?php
session_start();
$userRole = $_SESSION['user_role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minerva</title>
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-graduation-cap me-2"></i>
                Minerva
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <?php if ($userRole === 'teacher'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/teacher/dashboard">
                                <i class="fas fa-tachometer-alt me-1"></i>
                                Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/teacher/classrooms">
                                <i class="fas fa-chalkboard me-1"></i>
                                Classes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/teacher/create-work">
                                <i class="fas fa-file-alt me-1"></i>
                                Travaux
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/teacher/grade">
                                <i class="fas fa-chart-line me-1"></i>
                                Notes
                            </a>
                        </li>
                    <?php elseif ($userRole === 'student'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/student/dashboard">
                                <i class="fas fa-tachometer-alt me-1"></i>
                                Tableau de bord
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/student/courses">
                                <i class="fas fa-book me-1"></i>
                                Cours
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/student/grades">
                                <i class="fas fa-chart-line me-1"></i>
                                Mes notes
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <li class="nav-item">
                        <a class="nav-link active" href="/chat">
                            <i class="fas fa-comments me-1"></i>
                            Chat
                        </a>
                    </li>
                </ul>
                
                <div class="user-info">
                    <span class="badge bg-primary role-badge">
                        <?php echo $userRole === 'teacher' ? 'Enseignant' : 'Étudiant'; ?>
                    </span>
                    <span class="text-muted"><?php echo htmlspecialchars($userName); ?></span>
                    <a href="/logout" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>
