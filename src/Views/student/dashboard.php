<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Étudiant - Minerva</title>
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
        .welcome-card {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
                    <span class="badge bg-success role-badge"><?php echo htmlspecialchars($userRole); ?></span>
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

                <!-- Welcome card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card welcome-card">
                            <div class="card-body text-center">
                                <h3 class="mb-3">
                                    <i class="fas fa-user-graduate me-2"></i>
                                    Bienvenue <?= htmlspecialchars($user['name']) ?> ! 👋
                                </h3>
                                <p class="card-text mb-0">
                                    Bienvenue sur votre tableau de bord. Vous êtes connecté en tant qu'étudiant.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-users me-2"></i>
                                Ma classe
                            </div>
                            <div class="card-body">
                                <p class="card-text">Voir mes camarades et enseignants.</p>
                                <a href="/student/my-class" class="btn btn-info">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Voir ma classe
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
                                <p class="card-text">Discutez avec votre classe.</p>
                                <a href="/chat" class="btn btn-success">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Accéder au chat
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-clock me-2"></i>
                                Devoirs à rendre
                            </div>
                            <div class="card-body">
                                <p class="card-text">Voir les devoirs en cours.</p>
                                <a href="/student/submissions" class="btn btn-warning">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Voir les devoirs
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card dashboard-card">
                            <div class="card-header">
                                <i class="fas fa-chart-line me-2"></i>
                                Mes notes
                            </div>
                            <div class="card-body">
                                <p class="card-text">Consultez vos résultats.</p>
                                <a href="/student/grades" class="btn btn-secondary">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Voir mes notes
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