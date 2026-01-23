<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Classe - Minerva</title>
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
        .class-card {
            transition: transform 0.2s;
            height: 100%;
        }
        .class-card:hover {
            transform: translateY(-2px);
        }
        .class-card .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-weight: bold;
        }
        .person-card {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
            border-left: 4px solid #28a745;
            transition: transform 0.2s;
        }
        .person-card:hover {
            transform: translateX(5px);
        }
        .teacher-card {
            border-left-color: #007bff;
        }
        .person-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }
        .person-email {
            color: #6c757d;
            font-size: 0.875rem;
        }
        .welcome-card {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        .students-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
        }
        .badge-count {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            margin-left: 0.5rem;
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
                        <i class="fas fa-users me-2"></i>
                        Ma Classe
                    </h2>
                    <a href="/student/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au tableau de bord
                    </a>
                </div>

                <!-- Welcome card -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card welcome-card">
                            <div class="card-body text-center">
                                <h3 class="mb-3">
                                    <i class="fas fa-users me-2"></i>
                                    Mes classes
                                </h3>
                                <p class="card-text mb-0">
                                    Découvrez vos camarades et vos enseignants.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Classes grid -->
                <div class="row">
                    <?php if ($classrooms): ?>
                        <?php foreach ($classrooms as $classroom): ?>
                            <?php
                            // Récupérer les étudiants et l'enseignant de cette classe
                            $classModel = new \App\Models\ClassRoom();
                            $students = $classModel->getStudentsByClass($classroom['id']);
                            $teacher = $classModel->getTeacherByClass($classroom['id']);
                            ?>
                            
                            <div class="col-lg-6 mb-4">
                                <div class="card class-card">
                                    <div class="card-header">
                                        <i class="fas fa-chalkboard me-2"></i>
                                        <?= htmlspecialchars($classroom['name']) ?>
                                        <span class="badge-count"><?= count($students) ?> étudiants</span>
                                    </div>
                                    <div class="card-body">
                                        <?php if ($classroom['description']): ?>
                                            <p class="text-muted mb-3">
                                                <?= htmlspecialchars($classroom['description']) ?>
                                            </p>
                                        <?php endif; ?>
                                        
                                        <!-- Enseignant -->
                                        <?php if ($teacher): ?>
                                            <div class="mb-4">
                                                <h5 class="text-primary mb-3">
                                                    <i class="fas fa-chalkboard-teacher me-2"></i>
                                                    Enseignant
                                                </h5>
                                                <div class="person-card teacher-card">
                                                    <div class="person-name">
                                                        <i class="fas fa-user-tie me-2"></i>
                                                        <?= htmlspecialchars($teacher['name']) ?>
                                                    </div>
                                                    <div class="person-email">
                                                        <i class="fas fa-envelope me-2"></i>
                                                        <?= htmlspecialchars($teacher['email']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- Étudiants -->
                                        <div>
                                            <h5 class="text-success mb-3">
                                                <i class="fas fa-user-graduate me-2"></i>
                                                Étudiants
                                                <small class="text-muted">(<?= count($students) ?>)</small>
                                            </h5>
                                            <?php if ($students): ?>
                                                <div class="students-grid">
                                                    <?php foreach ($students as $student): ?>
                                                        <div class="person-card">
                                                            <div class="person-name">
                                                                <i class="fas fa-user me-2"></i>
                                                                <?= htmlspecialchars($student['name']) ?>
                                                            </div>
                                                            <div class="person-email">
                                                                <i class="fas fa-envelope me-2"></i>
                                                                <?= htmlspecialchars($student['email']) ?>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <div class="empty-state">
                                                    <i class="fas fa-users"></i>
                                                    <h6 class="mt-3">Aucun étudiant</h6>
                                                    <p class="text-muted">Cette classe ne contient aucun étudiant.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body empty-state">
                                    <i class="fas fa-users"></i>
                                    <h4 class="mt-3">Aucune classe</h4>
                                    <p class="text-muted">Vous n'êtes inscrit dans aucune classe pour le moment.</p>
                                </div>
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
