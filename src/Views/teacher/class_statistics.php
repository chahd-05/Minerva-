<?php
$userRole = $_SESSION['user_role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de Classe - Minerva</title>
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
        .chart-container {
            position: relative;
            height: 300px;
            margin: 20px 0;
        }
        .performance-excellent { color: #28a745; }
        .performance-good { color: #17a2b8; }
        .performance-average { color: #ffc107; }
        .performance-poor { color: #dc3545; }
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
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistiques de la Classe
                    </h2>
                    <div>
                        <a href="/teacher/statistics" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour
                        </a>
                        <a href="/teacher/attendance/stats?class_id=<?php echo $class['id']; ?>" class="btn btn-success me-2">
                            <i class="fas fa-user-check me-2"></i>
                            Présences
                        </a>
                        <a href="/teacher/grade" class="btn btn-info">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Notes
                        </a>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-chalkboard me-2"></i>
                            <?php echo htmlspecialchars($class['name']); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0"><?php echo htmlspecialchars($class['description'] ?? ''); ?></p>
                    </div>
                </div>

                <!-- Cartes de statistiques simples -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card stats-card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-user-check fa-2x mb-2"></i>
                                <h4><?php echo $attendanceStats['attendance_rate'] ?? 0; ?>%</h4>
                                <small>Taux de présence</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card stats-card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <h4><?php echo count($studentPerformance); ?></h4>
                                <small>Étudiants actifs</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphique simple -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Évolution des présences</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Performance par étudiant -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Performance par étudiant
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($studentPerformance)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune donnée de performance disponible.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Étudiant</th>
                                            <th>Présence</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($studentPerformance as $student): ?>
                                            <?php 
                                            $performanceClass = '';
                                            $performanceText = '';
                                            $rate = $student['attendance_rate'] ?? 0;
                                            
                                            if ($rate >= 90) {
                                                $performanceClass = 'text-success';
                                                $performanceText = 'Excellent';
                                            } elseif ($rate >= 75) {
                                                $performanceClass = 'text-info';
                                                $performanceText = 'Bon';
                                            } elseif ($rate >= 60) {
                                                $performanceClass = 'text-warning';
                                                $performanceText = 'Moyen';
                                            } else {
                                                $performanceClass = 'text-danger';
                                                $performanceText = 'Faible';
                                            }
                                            ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo htmlspecialchars($student['email']); ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success">
                                                        <?php echo $rate; ?>%
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="<?php echo $performanceClass; ?>">
                                                        <?php echo $performanceText; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Graphique simple d'évolution des présences
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        new Chart(attendanceCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_reverse(array_column($monthlyAttendance ?? [], 'month'))); ?>,
                datasets: [{
                    label: 'Taux de présence (%)',
                    data: <?php echo json_encode(array_reverse(array_column($monthlyAttendance ?? [], 'rate'))); ?>,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
</body>
</html>
