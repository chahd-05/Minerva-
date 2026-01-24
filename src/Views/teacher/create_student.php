<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un étudiant - Minerva</title>
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
        .create-student-card {
            transition: transform 0.2s;
        }
        .create-student-card:hover {
            transform: translateY(-2px);
        }
        .create-student-card .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-weight: bold;
        }
        .form-control:focus, .form-select:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-create {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 2rem;
            font-weight: bold;
            transition: transform 0.2s;
        }
        .btn-create:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
        }
        .info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
            border-left: 4px solid #28a745;
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        .feature-item i {
            color: #28a745;
            margin-right: 0.75rem;
            font-size: 1.25rem;
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
                        <i class="fas fa-user-plus me-2"></i>
                        Créer un étudiant
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

                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card create-student-card">
                            <div class="card-header">
                                <i class="fas fa-user-plus me-2"></i>
                                Assistant de création d'étudiant
                            </div>
                            <div class="card-body">
                                <!-- Info box -->
                                <div class="info-box">
                                    <h5 class="mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Que se passe-t-il après la création ?
                                    </h5>
                                    <div class="feature-item">
                                        <i class="fas fa-envelope"></i>
                                        <span>Un email avec les identifiants sera envoyé à l'étudiant</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-key"></i>
                                        <span>Un mot de passe temporaire sera généré automatiquement</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-users"></i>
                                        <span>L'étudiant sera immédiatement ajouté à la classe sélectionnée</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-sign-in-alt"></i>
                                        <span>L'étudiant pourra se connecter et accéder à ses cours</span>
                                    </div>
                                </div>

                                <form method="POST" action="/teacher/students/store">
                                    <div class="mb-4">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Nom complet de l'étudiant
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-lg" 
                                               id="name" 
                                               name="name" 
                                               required 
                                               placeholder="Ex: Jean Dupont">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-1"></i>
                                            Adresse email
                                        </label>
                                        <input type="email" 
                                               class="form-control form-control-lg" 
                                               id="email" 
                                               name="email" 
                                               required 
                                               placeholder="ex: etudiant@example.com">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            L'email sera utilisé pour envoyer les identifiants de connexion
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="class_id" class="form-label">
                                            <i class="fas fa-chalkboard me-1"></i>
                                            Classe d'affectation
                                        </label>
                                        <select class="form-select form-select-lg" id="class_id" name="class_id" required>
                                            <option value="">-- Choisir une classe --</option>
                                            <?php if ($classes): ?>
                                                <?php foreach ($classes as $class): ?>
                                                    <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <option value="">Aucune classe disponible</option>
                                            <?php endif; ?>
                                        </select>
                                        <?php if (!$classes): ?>
                                            <div class="alert alert-warning mt-2">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <strong>Attention :</strong> Vous devez d'abord créer une classe avant de pouvoir ajouter des étudiants.
                                                <a href="/teacher/classrooms" class="alert-link">Créer une classe maintenant</a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($classes): ?>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-create btn-lg">
                                                <i class="fas fa-user-plus me-2"></i>
                                                Créer l'étudiant
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>
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
                                        <a href="/teacher/classrooms" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-chalkboard me-2"></i>
                                            Gérer les classes
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="/teacher/classrooms/assign-students" class="btn btn-outline-info w-100">
                                            <i class="fas fa-users me-2"></i>
                                            Assigner des étudiants
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="/teacher/create-work" class="btn btn-outline-success w-100">
                                            <i class="fas fa-file-alt me-2"></i>
                                            Créer un travail
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
        // Auto-focus on name field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('name').focus();
        });

        // Email validation
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>
