<?php
$userRole = $_SESSION['user_role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de Présence - Minerva</title>
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
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .progress {
            height: 25px;
        }
        .attendance-rate {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .excellent { color: #28a745; }
        .good { color: #17a2b8; }
        .warning { color: #ffc107; }
        .danger { color: #dc3545; }
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
                        Statistiques de Présence
                    </h2>
                    <div>
                        <a href="/teacher/attendance/take?class_id=<?php echo $class['id']; ?>" class="btn btn-success me-2">
                            <i class="fas fa-user-check me-2"></i>
                            Prendre la présence
                        </a>
                        <a href="/teacher/attendance" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour
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

                <!-- Statistiques générales -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card stats-card bg-primary text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar fa-2x mb-2"></i>
                                <h4><?php echo $generalStats['session_count'] ?? 0; ?></h4>
                                <small>Jours de présence</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card bg-success text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-user-check fa-2x mb-2"></i>
                                <h4><?php echo $generalStats['total_present'] ?? 0; ?></h4>
                                <small>Total présents</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card bg-danger text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-user-times fa-2x mb-2"></i>
                                <h4><?php echo $generalStats['total_absent'] ?? 0; ?></h4>
                                <small>Total absents</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card bg-info text-white">
                            <div class="card-body text-center">
                                <i class="fas fa-percentage fa-2x mb-2"></i>
                                <h4>
                                    <?php 
                                    $total = ($generalStats['total_present'] ?? 0) + ($generalStats['total_absent'] ?? 0);
                                    $rate = $total > 0 ? round(($generalStats['total_present'] / $total) * 100, 1) : 0;
                                    echo $rate . '%';
                                    ?>
                                </h4>
                                <small>Taux de présence</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques par étudiant -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>
                            Présence par étudiant
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($studentStats)): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Aucune donnée de présence disponible. 
                                <a href="/teacher/attendance/take?class_id=<?php echo $class['id']; ?>" class="alert-link">
                                    Commencez par prendre la présence
                                </a>.
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Étudiant</th>
                                            <th>Email</th>
                                            <th>Présents</th>
                                            <th>Absents</th>
                                            <th>Total</th>
                                            <th>Taux de présence</th>
                                            <th>Performance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($studentStats as $student): ?>
                                            <?php 
                                            $rate = $student['total_sessions'] > 0 ? $student['attendance_rate'] : 0;
                                            $performanceClass = '';
                                            $performanceIcon = '';
                                            $performanceText = '';
                                            
                                            if ($rate >= 90) {
                                                $performanceClass = 'excellent';
                                                $performanceIcon = 'fa-trophy';
                                                $performanceText = 'Excellent';
                                            } elseif ($rate >= 75) {
                                                $performanceClass = 'good';
                                                $performanceIcon = 'fa-thumbs-up';
                                                $performanceText = 'Bon';
                                            } elseif ($rate >= 60) {
                                                $performanceClass = 'warning';
                                                $performanceIcon = 'fa-exclamation-triangle';
                                                $performanceText = 'Moyen';
                                            } else {
                                                $performanceClass = 'danger';
                                                $performanceIcon = 'fa-times-circle';
                                                $performanceText = 'Faible';
                                            }
                                            ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                                                </td>
                                                <td>
                                                    <small class="text-muted"><?php echo htmlspecialchars($student['email']); ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success"><?php echo $student['present_count']; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-danger"><?php echo $student['absent_count']; ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary"><?php echo $student['total_sessions']; ?></span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress flex-grow-1 me-2" style="height: 20px;">
                                                            <div class="progress-bar 
                                                                <?php echo $rate >= 75 ? 'bg-success' : ($rate >= 60 ? 'bg-warning' : 'bg-danger'); ?>" 
                                                                style="width: <?php echo $rate; ?>%">
                                                                <?php echo $rate; ?>%
                                                            </div>
                                                        </div>
                                                        <span class="attendance-rate <?php echo $performanceClass; ?>">
                                                            <?php echo $rate; ?>%
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo $performanceClass; ?>">
                                                        <i class="fas <?php echo $performanceIcon; ?> me-1"></i>
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

                <!-- Historique des présences -->
                <?php if (!empty($attendanceDates)): ?>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-history me-2"></i>
                                Historique des présences
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($attendanceDates as $date): ?>
                                    <div class="col-md-3 mb-2">
                                        <a href="/teacher/attendance/take?class_id=<?php echo $class['id']; ?>&date=<?php echo $date['date']; ?>" 
                                           class="btn btn-outline-primary w-100">
                                            <i class="fas fa-calendar-day me-2"></i>
                                            <?php echo date('d/m/Y', strtotime($date['date'])); ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
