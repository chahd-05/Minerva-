<?php
$userRole = $_SESSION['user_role'] ?? '';
$userName = $_SESSION['user_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prendre la Présence - Minerva</title>
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
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }
        .student-card:hover {
            transform: translateY(-2px);
        }
        .present {
            background-color: #d4edda;
            border-left-color: #28a745;
        }
        .absent {
            background-color: #f8d7da;
            border-left-color: #dc3545;
        }
        .attendance-toggle {
            cursor: pointer;
            transition: all 0.2s;
        }
        .attendance-toggle:hover {
            transform: scale(1.1);
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
                        <i class="fas fa-user-check me-2"></i>
                        Prendre la Présence
                    </h2>
                    <div>
                        <a href="/teacher/attendance" class="btn btn-secondary me-2">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour
                        </a>
                        <a href="/teacher/attendance/stats?class_id=<?php echo $class['id']; ?>" class="btn btn-info">
                            <i class="fas fa-chart-bar me-2"></i>
                            Statistiques
                        </a>
                    </div>
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
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">
                                    <i class="fas fa-chalkboard me-2"></i>
                                    <?php echo htmlspecialchars($class['name']); ?>
                                </h5>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?php echo date('d/m/Y', strtotime($date)); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-0"><?php echo htmlspecialchars($class['description'] ?? ''); ?></p>
                    </div>
                </div>

                <?php if ($attendanceTaken): ?>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        La présence a déjà été prise pour cette date. Vous pouvez la modifier ci-dessous.
                    </div>
                <?php endif; ?>

                <form method="POST" action="/teacher/attendance/save">
                    <input type="hidden" name="class_id" value="<?php echo $class['id']; ?>">
                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h4 class="text-success mb-1">
                                        <i class="fas fa-user-check me-2"></i>
                                        <span id="present-count">0</span>
                                    </h4>
                                    <small class="text-muted">Présents</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h4 class="text-danger mb-1">
                                        <i class="fas fa-user-times me-2"></i>
                                        <span id="absent-count">0</span>
                                    </h4>
                                    <small class="text-muted">Absents</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <?php if (empty($students)): ?>
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Aucun étudiant dans cette classe. 
                                    <a href="/teacher/classrooms/assign-students?id=<?php echo $class['id']; ?>" class="alert-link">
                                        Ajoutez des étudiants d'abord
                                    </a>.
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                                <?php 
                                $isPresent = false;
                                foreach ($existingAttendance as $attendance) {
                                    if ($attendance['student_id'] == $student['id']) {
                                        $isPresent = ($attendance['status'] == 1); // 1 = present
                                        break;
                                    }
                                }
                                ?>
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card student-card <?php echo $isPresent ? 'present' : 'absent'; ?>" 
                                         id="student-<?php echo $student['id']; ?>">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1"><?php echo htmlspecialchars($student['name']); ?></h6>
                                                    <small class="text-muted"><?php echo htmlspecialchars($student['email']); ?></small>
                                                </div>
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="attendance[<?php echo $student['id']; ?>]" 
                                                           value="absent" id="hidden-<?php echo $student['id']; ?>">
                                                    <input class="form-check-input attendance-toggle" 
                                                           type="checkbox" 
                                                           id="attendance-<?php echo $student['id']; ?>"
                                                           <?php echo $isPresent ? 'checked' : ''; ?>
                                                           onchange="toggleAttendance(<?php echo $student['id']; ?>)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($students)): ?>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-primary" onclick="selectAllPresent()">
                                <i class="fas fa-check-double me-2"></i>
                                Tous présents
                            </button>
                            <button type="button" class="btn btn-outline-danger" onclick="selectAllAbsent()">
                                <i class="fas fa-times me-2"></i>
                                Tous absents
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                Enregistrer la présence
                            </button>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleAttendance(studentId) {
            const checkbox = document.getElementById('attendance-' + studentId);
            const hiddenInput = document.getElementById('hidden-' + studentId);
            const studentCard = document.getElementById('student-' + studentId);
            
            if (checkbox.checked) {
                hiddenInput.value = 'present';
                studentCard.classList.remove('absent');
                studentCard.classList.add('present');
            } else {
                hiddenInput.value = 'absent';
                studentCard.classList.remove('present');
                studentCard.classList.add('absent');
            }
            
            updateCounts();
        }
        
        function selectAllPresent() {
            document.querySelectorAll('.attendance-toggle').forEach(checkbox => {
                checkbox.checked = true;
                const studentId = checkbox.id.replace('attendance-', '');
                toggleAttendance(studentId);
            });
        }
        
        function selectAllAbsent() {
            document.querySelectorAll('.attendance-toggle').forEach(checkbox => {
                checkbox.checked = false;
                const studentId = checkbox.id.replace('attendance-', '');
                toggleAttendance(studentId);
            });
        }
        
        function updateCounts() {
            const presentCount = document.querySelectorAll('.attendance-toggle:checked').length;
            const totalCount = document.querySelectorAll('.attendance-toggle').length;
            const absentCount = totalCount - presentCount;
            
            document.getElementById('present-count').textContent = presentCount;
            document.getElementById('absent-count').textContent = absentCount;
        }
        
        // Initialiser les compteurs au chargement
        document.addEventListener('DOMContentLoaded', function() {
            updateCounts();
        });
    </script>
</body>
</html>
