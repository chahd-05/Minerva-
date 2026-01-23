<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Classes - Minerva</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: bold;
        }
        .create-class-card {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .create-class-card .form-control,
        .create-class-card .form-label {
            color: #333;
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
                        <i class="fas fa-chalkboard me-2"></i>
                        Mes Classes
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

                <div class="row">
                    <!-- Carte de création de classe -->
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card class-card create-class-card">
                            <div class="card-header">
                                <i class="fas fa-plus-circle me-2"></i>
                                Créer une nouvelle classe
                            </div>
                            <div class="card-body">
                                <form method="POST" action="/teacher/classrooms/store">
                                    <div class="mb-3">
                                        <label for="className" class="form-label">Nom de la classe</label>
                                        <input type="text" class="form-control" id="className" name="name" required 
                                               placeholder="Ex: Mathématiques 3ème A">
                                    </div>
                                    <div class="mb-3">
                                        <label for="classDescription" class="form-label">Description</label>
                                        <textarea class="form-control" id="classDescription" name="description" rows="3"
                                                  placeholder="Description de la classe (optionnel)"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-light btn-sm w-100">
                                        <i class="fas fa-plus me-2"></i>
                                        Créer la classe
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Cartes des classes existantes -->
                    <?php if ($classes): ?>
                        <?php foreach ($classes as $class): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card class-card">
                                    <div class="card-header">
                                        <i class="fas fa-chalkboard me-2"></i>
                                        <?php echo htmlspecialchars($class['name']); ?>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-3">
                                            <?php echo htmlspecialchars($class['description'] ?? 'Pas de description'); ?>
                                        </p>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="/teacher/classrooms/assign-students?id=<?php echo $class['id']; ?>" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-users me-2"></i>
                                                Gérer les étudiants
                                            </a>
                                            <a href="/teacher/attendance/take?class_id=<?php echo $class['id']; ?>" 
                                               class="btn btn-success btn-sm">
                                                <i class="fas fa-user-check me-2"></i>
                                                Prendre la présence
                                            </a>
                                            <a href="/teacher/create-work?class_id=<?php echo $class['id']; ?>" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-file-alt me-2"></i>
                                                Créer un devoir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>
                                Vous n'avez aucune classe créée pour le moment. 
                                Utilisez le formulaire ci-dessus pour créer votre première classe.
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
