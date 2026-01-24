<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Enseignant - Minerva</title>
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
        .dashboard-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .dashboard-card:hover {
            transform: translateY(-2px);
        }
        .dashboard-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
        }
        .dashboard-card .card-body {
            padding: 1.5rem;
        }
        .dashboard-card .card-text {
            color: #6c757d;
            margin-bottom: 1rem;
        }
        .dashboard-card .btn {
            width: 100%;
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
                        <i class="fas fa-tachometer-alt me-2"></i>
                        Tableau de bord
                    </h2>
                </div>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php 
                        echo htmlspecialchars($_SESSION['success']); 
                        unset($_SESSION['success']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php 
                        echo htmlspecialchars($_SESSION['error']); 
                        unset($_SESSION['error']);
                        ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-chalkboard me-2"></i>
                                Classes
                            </div>
                            <div class="card-body">
                                <p class="card-text">Créez et suivez des classes.</p>
                                <a href="/teacher/classrooms" class="btn btn-primary">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Gérer les classes
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-comments me-2"></i>
                                Chat de classe
                            </div>
                            <div class="card-body">
                                <p class="card-text">Discutez avec vos étudiants.</p>
                                <a href="/chat" class="btn btn-info">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Accéder au chat
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-user-check me-2"></i>
                                Présence
                            </div>
                            <div class="card-body">
                                <p class="card-text">Prenez la présence et consultez les statistiques.</p>
                                <a href="/teacher/attendance" class="btn btn-warning">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Gérer la présence
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-chart-bar me-2"></i>
                                Statistiques
                            </div>
                            <div class="card-body">
                                <p class="card-text">Voyez les statistiques sur vos classes et étudiants.</p>
                                <a href="/teacher/statistics" class="btn btn-secondary">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Voir les statistiques
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-user-plus me-2"></i>
                                Créer étudiants
                            </div>
                            <div class="card-body">
                                <p class="card-text">Créez des comptes étudiants.</p>
                                <a href="/teacher/students/create" class="btn btn-primary">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Créer un étudiant
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-file-alt me-2"></i>
                                Créer un devoir
                            </div>
                            <div class="card-body">
                                <p class="card-text">Créez de nouveaux devoirs pour vos classes.</p>
                                <a href="/teacher/create-work" class="btn btn-success">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Créer un devoir
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-tasks me-2"></i>
                                Assigner un devoir
                            </div>
                            <div class="card-body">
                                <p class="card-text">Assignez les devoirs aux étudiants.</p>
                                <a href="/teacher/assignwork" class="btn btn-info">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Assigner un devoir
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-graduation-cap me-2"></i>
                                Noter les travaux
                            </div>
                            <div class="card-body">
                                <p class="card-text">Notez les travaux soumis par vos étudiants.</p>
                                <a href="/teacher/grade" class="btn btn-warning">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Noter les travaux
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>