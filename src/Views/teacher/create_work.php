<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
$classId = $_GET['class_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un devoir - Minerva</title>
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
        .create-work-card {
            transition: transform 0.2s;
        }
        .create-work-card:hover {
            transform: translateY(-2px);
        }
        .create-work-card .card-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }
        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
            transform: translateY(-1px);
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
                        <i class="fas fa-file-alt me-2"></i>
                        Créer un devoir
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
                        <div class="card create-work-card">
                            <div class="card-header">
                                <i class="fas fa-plus-circle me-2"></i>
                                Nouveau devoir
                            </div>
                            <div class="card-body">
                                <form method="POST" action="/teacher/create-work" enctype="multipart/form-data">
                                    <div class="mb-4">
                                        <label for="title" class="form-label">
                                            <i class="fas fa-heading me-1"></i>
                                            Titre du devoir
                                        </label>
                                        <input type="text" class="form-control" id="title" name="title" required 
                                               placeholder="Ex: Devoir de mathématiques">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="description" class="form-label">
                                            <i class="fas fa-align-left me-1"></i>
                                            Description
                                        </label>
                                        <textarea class="form-control" id="description" name="description" required 
                                                  placeholder="Décrivez le devoir en détail..." rows="4"></textarea>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="deadline" class="form-label">
                                                <i class="fas fa-calendar me-1"></i>
                                                Date limite
                                            </label>
                                            <input type="date" class="form-control" id="deadline" name="deadline" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-4">
                                            <label for="classroom_id" class="form-label">
                                                <i class="fas fa-chalkboard me-1"></i>
                                                Classe
                                            </label>
                                            <select class="form-select" id="classroom_id" name="classroom_id" required>
                                                <option value="">-- Choisir une classe --</option>
                                                <?php if ($classes): ?>
                                                    <?php foreach ($classes as $class): ?>
                                                        <option value="<?= $class['id'] ?>" <?= ($classId == $class['id']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($class['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <option value="">Aucune classe disponible</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="file" class="form-label">
                                            <i class="fas fa-paperclip me-1"></i>
                                            Fichier (optionnel)
                                        </label>
                                        <input type="file" class="form-control" id="file" name="file" 
                                               accept=".pdf,.doc,.docx,.txt">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Formats acceptés : PDF, DOC, DOCX, TXT (max 10MB)
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-plus me-2"></i>
                                            Créer le devoir
                                        </button>
                                    </div>
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
                                            Voir mes classes
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="/teacher/assignwork" class="btn btn-outline-info w-100">
                                            <i class="fas fa-tasks me-2"></i>
                                            Assigner un devoir
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="/teacher/grade" class="btn btn-outline-warning w-100">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            Noter les travaux
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
        // Set minimum date to today
        document.getElementById('deadline').min = new Date().toISOString().split('T')[0];
        
        // Auto-focus on title field
        document.getElementById('title').focus();
    </script>
</body>
</html>
