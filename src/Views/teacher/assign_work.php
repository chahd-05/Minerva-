<?php
$userRole = $_SESSION['role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigner un travail - Minerva</title>
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
        .assign-card {
            transition: transform 0.2s;
        }
        .assign-card:hover {
            transform: translateY(-2px);
        }
        .assign-card .card-header {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            font-weight: bold;
        }
        .class-selected {
            background: linear-gradient(135deg, #e9ecef 0%, #f8f9fa 100%);
            border: 2px solid #17a2b8;
            padding: 1rem;
            border-radius: 0.5rem;
            position: relative;
        }
        .checkbox-group {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            padding: 1rem;
            background: #f8f9fa;
        }
        .checkbox-item {
            margin-bottom: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }
        .checkbox-item:hover {
            background-color: #e9ecef;
        }
        .checkbox-item input[type="checkbox"] {
            width: auto;
            margin-right: 0.75rem;
            transform: scale(1.2);
        }
        .checkbox-item label {
            margin-bottom: 0;
            font-weight: 500;
            cursor: pointer;
        }
        .assigned-badge {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }
        .btn-assign {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border: none;
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 2rem;
            font-weight: bold;
            transition: transform 0.2s;
        }
        .btn-assign:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
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
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
        }
        .step {
            flex: 1;
            text-align: center;
            padding: 0.5rem;
            border-radius: 0.5rem;
            background: #e9ecef;
            margin: 0 0.25rem;
            position: relative;
        }
        .step.active {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        .step.completed {
            background: #28a745;
            color: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #17a2b8;
            box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
        }
        .work-info {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #17a2b8;
            margin-bottom: 1rem;
        }
    </style>
    <script>
        function updateStudentsList() {
            const workId = document.getElementById('work_id').value;
            const classId = '<?= $selectedClass ?>';
            
            if (workId && classId) {
                window.location.href = `/teacher/assignwork?class_id=${classId}&work_id=${workId}`;
            }
        }

        // Toggle all checkboxes
        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('.checkbox-item input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = source.checked;
            });
        }

        // Count selected students
        function updateSelectedCount() {
            const checked = document.querySelectorAll('.checkbox-item input[type="checkbox"]:checked').length;
            const total = document.querySelectorAll('.checkbox-item input[type="checkbox"]').length;
            const countElement = document.getElementById('selectedCount');
            if (countElement) {
                countElement.textContent = `${checked} sur ${total} étudiants sélectionnés`;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Update count when checkboxes change
            const checkboxes = document.querySelectorAll('.checkbox-item input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateSelectedCount);
            });
            updateSelectedCount();
        });
    </script>
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
                        <i class="fas fa-tasks me-2"></i>
                        Assigner un travail
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
                    <div class="col-md-10">
                        <div class="card assign-card">
                            <div class="card-header">
                                <i class="fas fa-clipboard-check me-2"></i>
                                Assistant d'assignation de travaux
                            </div>
                            <div class="card-body">
                                <!-- Step indicator -->
                                <div class="step-indicator">
                                    <div class="step <?= $selectedClass ? 'completed' : 'active' ?>">
                                        <i class="fas fa-chalkboard me-1"></i>
                                        <small>1. Choisir la classe</small>
                                    </div>
                                    <div class="step <?= $selectedWork ? 'completed' : ($selectedClass ? 'active' : '') ?>">
                                        <i class="fas fa-file-alt me-1"></i>
                                        <small>2. Choisir le travail</small>
                                    </div>
                                    <div class="step <?= ($selectedClass && $selectedWork) ? 'active' : '' ?>">
                                        <i class="fas fa-users me-1"></i>
                                        <small>3. Sélectionner les étudiants</small>
                                    </div>
                                </div>

                                <?php if (!$selectedClass): ?>
                                    <!-- Step 1: Choose class -->
                                    <form method="GET" action="/teacher/assignwork">
                                        <div class="mb-4">
                                            <label for="class_id" class="form-label">
                                                <i class="fas fa-chalkboard me-1"></i>
                                                Étape 1 : Choisir une classe
                                            </label>
                                            <select class="form-select form-select-lg" id="class_id" name="class_id" required onchange="this.form.submit()">
                                                <option value="">-- Choisir une classe --</option>
                                                <?php if ($classes): ?>
                                                    <?php foreach ($classes as $class): ?>
                                                        <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" action="/teacher/assignwork">
                                        <input type="hidden" name="class_id" value="<?= $selectedClass ?>">
                                        <input type="hidden" name="work_id" value="<?= $selectedWork ?>">
                                        
                                        <!-- Selected class info -->
                                        <div class="mb-4">
                                            <label class="form-label">
                                                <i class="fas fa-chalkboard me-1"></i>
                                                Classe sélectionnée
                                            </label>
                                            <div class="class-selected">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong><?= htmlspecialchars($classes[array_search($selectedClass, array_column($classes, 'id'))]['name']) ?></strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-users me-1"></i>
                                                            <?= count($students) ?> étudiants dans cette classe
                                                        </small>
                                                    </div>
                                                    <a href="/teacher/assignwork" class="btn btn-outline-secondary btn-sm">
                                                        <i class="fas fa-exchange-alt me-1"></i>
                                                        Changer
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Work selection -->
                                        <div class="mb-4">
                                            <label for="work_id" class="form-label">
                                                <i class="fas fa-file-alt me-1"></i>
                                                Étape 2 : Choisir le travail à assigner
                                            </label>
                                            <select class="form-select form-select-lg" id="work_id" name="work_id" required onchange="updateStudentsList()">
                                                <option value="">-- Choisir un travail --</option>
                                                <?php if ($works): ?>
                                                    <?php foreach ($works as $work): ?>
                                                        <option value="<?= $work['id'] ?>" <?= ($selectedWork == $work['id']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($work['title']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <option value="">Aucun travail disponible</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <?php if ($selectedWork): ?>
                                            <!-- Work info -->
                                            <?php
                                            $selectedWorkData = null;
                                            foreach ($works as $work) {
                                                if ($work['id'] == $selectedWork) {
                                                    $selectedWorkData = $work;
                                                    break;
                                                }
                                            }
                                            ?>
                                            <?php if ($selectedWorkData): ?>
                                                <div class="work-info">
                                                    <h6><i class="fas fa-info-circle me-1"></i> Détails du travail</h6>
                                                    <?php if (!empty($selectedWorkData['description'])): ?>
                                                        <p class="mb-2"><?= nl2br(htmlspecialchars($selectedWorkData['description'])) ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($selectedWorkData['deadline'])): ?>
                                                        <small class="text-muted">
                                                            <i class="fas fa-calendar me-1"></i>
                                                            Date limite : <?= date('d/m/Y', strtotime($selectedWorkData['deadline'])) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        
                                        <!-- Students selection -->
                                        <div class="mb-4">
                                            <label class="form-label">
                                                <i class="fas fa-users me-1"></i>
                                                Étape 3 : Sélectionner les étudiants
                                            </label>
                                            <?php if ($students): ?>
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="selectAll" onchange="toggleAll(this)">
                                                        <label class="form-check-label" for="selectAll">
                                                            Tout sélectionner / désélectionner
                                                        </label>
                                                    </div>
                                                    <small class="text-muted" id="selectedCount">0 étudiants sélectionnés</small>
                                                </div>
                                                
                                                <div class="checkbox-group">
                                                    <?php foreach ($students as $student): ?>
                                                        <div class="checkbox-item">
                                                            <input type="checkbox" 
                                                                   name="student_ids[]" 
                                                                   value="<?= $student['id'] ?>" 
                                                                   id="student_<?= $student['id'] ?>"
                                                                   class="student-checkbox"
                                                                   <?= ($student['is_assigned'] ?? 0) ? 'checked' : '' ?>>
                                                            <label for="student_<?= $student['id'] ?>">
                                                                <?= htmlspecialchars($student['name']) ?>
                                                                <?php if ($student['is_assigned'] ?? 0): ?>
                                                                    <span class="assigned-badge">
                                                                        <i class="fas fa-check me-1"></i>
                                                                        déjà assigné
                                                                    </span>
                                                                <?php endif; ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Cochez les étudiants pour assigner ce travail, décochez pour le retirer
                                                </small>
                                            <?php else: ?>
                                                <div class="empty-state">
                                                    <i class="fas fa-users"></i>
                                                    <h6 class="mt-3">Aucun étudiant</h6>
                                                    <p class="text-muted">Cette classe ne contient aucun étudiant.</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <?php if ($students && $works && $selectedWork): ?>
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-assign btn-lg">
                                                    <i class="fas fa-clipboard-check me-2"></i>
                                                    Assigner le travail
                                                </button>
                                            </div>
                                        <?php endif; ?>
                                    </form>
                                <?php endif; ?>
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
                                        <a href="/teacher/create-work" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-file-alt me-2"></i>
                                            Créer un travail
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="/teacher/grade" class="btn btn-outline-success w-100">
                                            <i class="fas fa-graduation-cap me-2"></i>
                                            Noter les travaux
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
</body>
</html>
