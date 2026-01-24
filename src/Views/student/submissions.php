<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes travaux - Minerva</title>
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
        .work-card .card-body {
            padding: 1.5rem;
        }
        .work-info {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
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
            color: #ffc107;
            margin-right: 0.5rem;
        }
        .deadline {
            color: #dc3545;
            font-weight: 600;
        }
        .deadline-soon {
            color: #fd7e14;
        }
        .deadline-passed {
            color: #dc3545;
        }
        .submission-content {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #28a745;
            margin: 1rem 0;
            white-space: pre-wrap;
            font-family: 'Courier New', monospace;
        }
        .form-control:focus {
            border-color: #ffc107;
            box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        }
        .btn-submit {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 2rem;
            font-weight: bold;
            transition: transform 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #ff8c00 0%, #fd7e14 100%);
        }
        .btn-submit:disabled {
            background: #6c757d;
            transform: none;
        }
        .welcome-card {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
        }
        .welcome-card .card-text {
            color: rgba(255, 255, 255, 0.9);
        }
        .submitted-badge {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 1rem;
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
        .works-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            gap: 1.5rem;
        }
        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 0.5rem;
        }
        .status-pending {
            background: #ffc107;
        }
        .status-submitted {
            background: #28a745;
        }
        .status-graded {
            background: #17a2b8;
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
                        <i class="fas fa-file-alt me-2"></i>
                        Mes travaux
                    </h2>
                    <a href="/student/dashboard" class="btn btn-secondary">
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

                <!-- Welcome card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card welcome-card">
                            <div class="card-body text-center">
                                <h3 class="mb-3">
                                    <i class="fas fa-file-alt me-2"></i>
                                    Mes travaux à faire
                                </h3>
                                <p class="card-text mb-0">
                                    Consultez et soumettez vos travaux assignés.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Works grid -->
                <div class="works-grid">
                    <?php if ($works): ?>
                        <?php foreach ($works as $work): ?>
                            <div class="card work-card">
                                <div class="card-header">
                                    <i class="fas fa-tasks me-2"></i>
                                    <?= htmlspecialchars($work['title']) ?>
                                    <?php if ($work['submission_id']): ?>
                                        <span class="status-indicator status-submitted"></span>
                                    <?php else: ?>
                                        <span class="status-indicator status-pending"></span>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <div class="work-info">
                                        <div class="work-info-item">
                                            <i class="fas fa-chalkboard"></i>
                                            <?= htmlspecialchars($work['classroom_name']) ?>
                                        </div>
                                        <div class="work-info-item">
                                            <i class="fas fa-calendar"></i>
                                            <?php
                                            $deadline = new DateTime($work['deadline']);
                                            $now = new DateTime();
                                            $interval = $now->diff($deadline);
                                            
                                            if ($now > $deadline) {
                                                echo '<span class="deadline deadline-passed">Expiré</span>';
                                            } elseif ($interval->days <= 3) {
                                                echo '<span class="deadline deadline-soon">' . $interval->days . ' jour(s)</span>';
                                            } else {
                                                echo '<span class="deadline">' . $interval->days . ' jour(s)</span>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    
                                    <?php if ($work['description']): ?>
                                        <div class="mb-3">
                                            <h6 class="text-muted">Description:</h6>
                                            <p class="text-muted"><?= nl2br(htmlspecialchars($work['description'])) ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <!-- État de la soumission -->
                                    <?php if ($work['submission_id']): ?>
                                        <div class="submitted-badge">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Soumis le <?= date('d/m/Y à H:i', strtotime($work['submitted_at'])) ?>
                                        </div>
                                        <div class="text-muted mb-3">
                                            <i class="fas fa-hourglass-half me-2"></i>
                                            En attente de correction
                                        </div>
                                        <?php if ($work['content']): ?>
                                            <div class="submission-content">
                                                <strong>Votre réponse:</strong><br>
                                                <?= nl2br(htmlspecialchars($work['content'])) ?>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!-- Formulaire de soumission -->
                                        <form method="POST" action="/student/submissions" class="mt-3">
                                            <input type="hidden" name="work_id" value="<?= $work['id'] ?>">
                                            
                                            <div class="mb-3">
                                                <label for="content_<?= $work['id'] ?>" class="form-label">
                                                    <i class="fas fa-edit me-1"></i>
                                                    Votre réponse
                                                </label>
                                                <textarea class="form-control" 
                                                          id="content_<?= $work['id'] ?>" 
                                                          name="content" 
                                                          required 
                                                          rows="4"
                                                          placeholder="Écrivez votre réponse ici..."></textarea>
                                            </div>
                                            
                                            <button type="submit" class="btn btn-submit w-100">
                                                <i class="fas fa-paper-plane me-2"></i>
                                                Soumettre le travail
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="card">
                            <div class="card-body empty-state">
                                <i class="fas fa-file-alt"></i>
                                <h4 class="mt-3">Aucun travail</h4>
                                <p class="text-muted">Vous n'avez aucun travail assigné pour le moment.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-focus sur le premier textarea
        document.addEventListener('DOMContentLoaded', function() {
            const firstTextarea = document.querySelector('textarea');
            if (firstTextarea) {
                firstTextarea.focus();
            }
        });

        // Auto-resize des textareas
        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = this.scrollHeight + 'px';
            });
        });
    </script>
</body>
</html>
