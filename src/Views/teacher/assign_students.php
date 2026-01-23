<?php
$userRole = $_SESSION['user_role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner des étudiants - Minerva</title>
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
        .student-card {
            transition: transform 0.2s;
        }
        .student-card:hover {
            transform: translateY(-2px);
        }
        .assigned-student {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
        }
        .unassigned-student {
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
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
                        <i class="fas fa-users me-2"></i>
                        Gérer les étudiants de la classe
                    </h2>
                    <a href="/teacher/classrooms" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour aux classes
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

                <div class="row">
                    <!-- Étudiants assignés -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-check me-2"></i>
                                    Étudiants assignés (<?php echo count($assignedStudents); ?>)
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($assignedStudents)): ?>
                                    <p class="text-muted">Aucun étudiant assigné à cette classe.</p>
                                <?php else: ?>
                                    <?php foreach ($assignedStudents as $student): ?>
                                        <div class="card student-card assigned-student mb-2">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($student['email']); ?></small>
                                                    </div>
                                                    <form method="POST" action="/teacher/classrooms/remove-student" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer cet étudiant de la classe ?');">
                                                        <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                                                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Étudiants disponibles -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Étudiants disponibles (<?php echo count($unassignedStudents); ?>)
                                </h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($unassignedStudents)): ?>
                                    <p class="text-muted">Tous les étudiants sont déjà assignés à cette classe.</p>
                                <?php else: ?>
                                    <?php foreach ($unassignedStudents as $student): ?>
                                        <div class="card student-card unassigned-student mb-2">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                                                        <br>
                                                        <small class="text-muted"><?php echo htmlspecialchars($student['email']); ?></small>
                                                    </div>
                                                    <form method="POST" action="/teacher/classrooms/assign-student">
                                                        <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                                                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                        <button type="submit" class="btn btn-sm btn-primary">
                                                            <i class="fas fa-plus"></i> Assigner
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions supplémentaires -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-cog me-2"></i>
                            Actions rapides
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="/teacher/students/create" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Créer un nouvel étudiant
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-info w-100" onclick="location.reload()">
                                    <i class="fas fa-sync-alt me-2"></i>
                                    Actualiser la liste
                                </button>
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
