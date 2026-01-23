<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noter les travaux - Minerva</title>
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
        .work-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .work-card:hover {
            transform: translateY(-2px);
        }
        .work-card .card-header {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
            font-weight: bold;
        }
        .submission-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .submission-card:hover {
            transform: translateY(-2px);
        }
        .submission-card .card-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            font-weight: bold;
        }
        .submission-content {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin: 1rem 0;
            white-space: pre-wrap;
            border-left: 4px solid #17a2b8;
        }
        .grade-form {
            background: #e9ecef;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-top: 1rem;
        }
        .grade-input {
            text-align: center;
            font-size: 1.25rem;
            font-weight: bold;
        }
        .grade-input:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
        .btn-grade {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 2rem;
            font-weight: bold;
            transition: transform 0.2s;
        }
        .btn-grade:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
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
        .section-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .work-info {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .work-info-item {
            background: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            border: 1px solid #e9ecef;
        }
        .work-info-item i {
            margin-right: 0.5rem;
            color: #667eea;
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
                        <i class="fas fa-graduation-cap me-2"></i>
                        Noter les travaux
                    </h2>
                    <a href="/teacher/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au tableau de bord
                    </a>
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

                <!-- Section Travaux assignés -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="section-title">
                            <h4 class="mb-0">
                                <i class="fas fa-tasks me-2"></i>
                                Travaux assignés aux étudiants
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <?php if ($assignedWorks): ?>
                        <?php foreach ($assignedWorks as $work): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card work-card">
                                    <div class="card-header">
                                        <i class="fas fa-file-alt me-2"></i>
                                        <?= htmlspecialchars($work['title']) ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="work-info">
                                            <div class="work-info-item">
                                                <i class="fas fa-chalkboard"></i>
                                                <?= htmlspecialchars($work['classroom_name']) ?>
                                            </div>
                                            <div class="work-info-item">
                                                <i class="fas fa-users"></i>
                                                <?= $work['assigned_students'] ?> étudiants
                                            </div>
                                            <div class="work-info-item">
                                                <i class="fas fa-calendar"></i>
                                                <?= date('d/m/Y', strtotime($work['deadline'])) ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ($work['description']): ?>
                                            <div class="mt-3">
                                                <small class="text-muted">Description:</small>
                                                <p class="mb-0"><?= nl2br(htmlspecialchars($work['description'])) ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body empty-state">
                                    <i class="fas fa-tasks"></i>
                                    <h5 class="mt-3">Aucun travail assigné</h5>
                                    <p class="text-muted">Vous n'avez assigné aucun travail pour le moment.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Section Soumissions à noter -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="section-title">
                            <h4 class="mb-0">
                                <i class="fas fa-pen me-2"></i>
                                Soumissions à noter
                            </h4>
                        </div>
                    </div>
                </div>

                <?php 
                // Afficher les soumissions existantes pour noter
                $model = new \App\Models\Grade();
                $submissions = $model->getSubmissions();
                ?>

                <div class="row">
                    <?php if ($submissions): ?>
                        <?php foreach ($submissions as $s): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card submission-card">
                                    <div class="card-header">
                                        <i class="fas fa-user me-2"></i>
                                        <?= htmlspecialchars($s['name']) ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="submission-content">
                                            <?= htmlspecialchars($s['content']) ?>
                                        </div>

                                        <form method="POST" action="/teacher/grade" class="grade-form">
                                            <input type="hidden" name="submission_id" value="<?= $s['id'] ?>">
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="score_<?= $s['id'] ?>" class="form-label">
                                                        <i class="fas fa-star me-1"></i>
                                                        Note (0-20)
                                                    </label>
                                                    <input type="number" 
                                                           class="form-control grade-input" 
                                                           id="score_<?= $s['id'] ?>" 
                                                           name="score" 
                                                           min="0" 
                                                           max="20" 
                                                           required 
                                                           placeholder="20">
                                                </div>
                                                
                                                <div class="col-md-6 mb-3">
                                                    <label for="comment_<?= $s['id'] ?>" class="form-label">
                                                        <i class="fas fa-comment me-1"></i>
                                                        Commentaire
                                                    </label>
                                                    <input type="text" 
                                                           class="form-control" 
                                                           id="comment_<?= $s['id'] ?>" 
                                                           name="comment" 
                                                           placeholder="Excellent travail !">
                                                </div>
                                            </div>
                                            
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-grade">
                                                    <i class="fas fa-check me-2"></i>
                                                    Noter le travail
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body empty-state">
                                    <i class="fas fa-pen"></i>
                                    <h5 class="mt-3">Aucune soumission à noter</h5>
                                    <p class="text-muted">Aucun étudiant n'a soumis de travail pour le moment.</p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Actions rapides -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-bolt me-2"></i>
                                Actions rapides
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <a href="/teacher/create-work" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-file-alt me-2"></i>
                                            Créer un devoir
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="/teacher/assignwork" class="btn btn-outline-info w-100">
                                            <i class="fas fa-tasks me-2"></i>
                                            Assigner un devoir
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="/teacher/classrooms" class="btn btn-outline-warning w-100">
                                            <i class="fas fa-chalkboard me-2"></i>
                                            Gérer les classes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus sur le premier input de note
        document.addEventListener('DOMContentLoaded', function() {
            const firstGradeInput = document.querySelector('.grade-input');
            if (firstGradeInput) {
                firstGradeInput.focus();
            }
        });

        // Validation des notes
        document.querySelectorAll('.grade-input').forEach(input => {
            input.addEventListener('input', function() {
                const value = parseFloat(this.value);
                if (value < 0) this.value = 0;
                if (value > 20) this.value = 20;
            });
        });
    </script>
</body>
</html>
