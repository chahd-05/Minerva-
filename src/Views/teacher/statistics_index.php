<?php
$userRole = $_SESSION['user_role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Minerva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .class-card {
            transition: transform 0.2s;
        }
        .class-card:hover {
            transform: translateY(-2px);
        }
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
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
                        <i class="fas fa-chart-line me-2"></i>
                        Statistiques Générales
                    </h2>
                    <a href="/teacher/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au tableau de bord
                    </a>
                </div>

                <!-- Cartes de statistiques générales simples -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card stats-card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-chalkboard fa-2x mb-2"></i>
                                <h4><?php echo $teacherStats['total_classes'] ?? 0; ?></h4>
                                <small>Classes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h4><?php echo $teacherStats['total_students'] ?? 0; ?></h4>
                                <small>Étudiants</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stats-card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-check fa-2x mb-2"></i>
                                <h4><?php echo $teacherStats['attendance_days'] ?? 0; ?></h4>
                                <small>Jours de présence</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphique simple -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Vue d'ensemble simple
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="overviewChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Liste des classes avec statistiques rapides -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Classes et Statistiques
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($classes)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Vous n'avez aucune classe. 
                                <a href="/teacher/classrooms" class="alert-link">Créez une classe d'abord</a>.
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($classes as $class): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card class-card h-100">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-chalkboard me-2"></i>
                                                    <?php echo htmlspecialchars($class['name']); ?>
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <p class="text-muted small mb-3">
                                                    <?php echo htmlspecialchars($class['description'] ?? 'Pas de description'); ?>
                                                </p>
                                                
                                                <div class="d-grid gap-2">
                                                    <a href="/teacher/statistics/class?class_id=<?php echo $class['id']; ?>" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-chart-bar me-2"></i>
                                                        Voir les statistiques
                                                    </a>
                                                    <a href="/teacher/attendance/stats?class_id=<?php echo $class['id']; ?>" 
                                                       class="btn btn-success btn-sm">
                                                        <i class="fas fa-user-check me-2"></i>
                                                        Présences
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Graphique simple de répartition
        const overviewCtx = document.getElementById('overviewChart').getContext('2d');
        new Chart(overviewCtx, {
            type: 'bar',
            data: {
                labels: ['Classes', 'Étudiants', 'Jours de présence'],
                datasets: [{
                    label: 'Total',
                    data: [
                        <?php echo $teacherStats['total_classes'] ?? 0; ?>,
                        <?php echo $teacherStats['total_students'] ?? 0; ?>,
                        <?php echo $teacherStats['attendance_days'] ?? 0; ?>
                    ],
                    backgroundColor: ['#007bff', '#28a745', '#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Vue d\'ensemble simple'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
