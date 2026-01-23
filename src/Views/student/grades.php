<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes notes - Minerva</title>
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
        .grade-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .grade-card:hover {
            transform: translateY(-2px);
        }
        .grade-card .card-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            font-weight: bold;
        }
        .grade-score {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        .score-excellent {
            color: #28a745;
        }
        .score-good {
            color: #17a2b8;
        }
        .score-average {
            color: #ffc107;
        }
        .score-poor {
            color: #dc3545;
        }
        .grade-info {
            color: #6c757d;
            margin-bottom: 1rem;
        }
        .grade-comment {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #17a2b8;
            margin-top: 1rem;
        }
        .welcome-card {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        .welcome-card .card-text {
            color: rgba(255, 255, 255, 0.9);
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
        .grades-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }
        .grade-badge {
            font-size: 0.875rem;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-weight: 600;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-bottom: 2rem;
        }
        .stat-item {
            text-align: center;
            padding: 1rem;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            font-size: 0.875rem;
            opacity: 0.9;
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
                        <i class="fas fa-chart-line me-2"></i>
                        Mes notes
                    </h2>
                    <a href="/student/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au tableau de bord
                    </a>
                </div>

                <!-- Stats card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card stats-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <div class="stat-number">
                                                <?php 
                                                $average = 0;
                                                if ($grades && count($grades) > 0) {
                                                    $total = array_sum(array_column($grades, 'score'));
                                                    $average = round($total / count($grades), 1);
                                                }
                                                echo $average;
                                                ?>
                                            </div>
                                            <div class="stat-label">Moyenne générale</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <div class="stat-number"><?= count($grades) ?></div>
                                            <div class="stat-label">Travaux notés</div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="stat-item">
                                            <div class="stat-number">
                                                <?php 
                                                $excellent = 0;
                                                if ($grades) {
                                                    foreach ($grades as $grade) {
                                                        if ($grade['score'] >= 16) $excellent++;
                                                    }
                                                }
                                                echo $excellent;
                                                ?>
                                            </div>
                                            <div class="stat-label">Notes excellentes</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Welcome card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card welcome-card">
                            <div class="card-body text-center">
                                <h3 class="mb-3">
                                    <i class="fas fa-chart-line me-2"></i>
                                    Vos résultats
                                </h3>
                                <p class="card-text mb-0">
                                    Voici l'historique de vos notes et commentaires.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grades grid -->
                <div class="grades-grid">
                    <?php if ($grades): ?>
                        <?php foreach ($grades as $grade): ?>
                            <div class="card grade-card">
                                <div class="card-header">
                                    <i class="fas fa-tasks me-2"></i>
                                    <?= htmlspecialchars($grade['work_title']) ?>
                                </div>
                                <div class="card-body">
                                    <div class="grade-info">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span>
                                                <i class="fas fa-chalkboard me-1"></i>
                                                <?= htmlspecialchars($grade['classroom_name']) ?>
                                            </span>
                                            <span class="grade-badge bg-secondary">
                                                <?= date('d/m/Y', strtotime($grade['submitted_at'])) ?>
                                            </span>
                                        </div>
                                        <div class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            Soumis le <?= date('d/m/Y à H:i', strtotime($grade['submitted_at'])) ?>
                                        </div>
                                    </div>
                                    
                                    <div class="grade-score text-center">
                                        <?php
                                        $score = $grade['score'];
                                        $scoreClass = 'score-average';
                                        if ($score >= 16) $scoreClass = 'score-excellent';
                                        elseif ($score >= 12) $scoreClass = 'score-good';
                                        elseif ($score >= 8) $scoreClass = 'score-average';
                                        else $scoreClass = 'score-poor';
                                        ?>
                                        <span class="<?= $scoreClass ?>">
                                            <i class="fas fa-star me-2"></i>
                                            <?= $score ?>/20
                                        </span>
                                    </div>
                                    
                                    <?php if ($grade['comment']): ?>
                                        <div class="grade-comment">
                                            <h6 class="mb-2">
                                                <i class="fas fa-comment me-1"></i>
                                                Commentaire du professeur
                                            </h6>
                                            <?= nl2br(htmlspecialchars($grade['comment'])) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-body empty-state">
                                <i class="fas fa-chart-line"></i>
                                <h4 class="mt-3">Aucune note</h4>
                                <p class="text-muted">Vous n'avez aucune note pour le moment.</p>
                                <p class="text-muted">Vos travaux soumis apparaîtront ici une fois notés par vos professeurs.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
