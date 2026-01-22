<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Assigner un travail</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .header { background: #007bff; color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .form-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        .btn { background: #007bff; color: white; padding: 12px 24px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .back-link { color: #007bff; text-decoration: none; margin-bottom: 20px; display: inline-block; }
        .checkbox-group { max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 15px; border-radius: 5px; }
        .checkbox-item { margin-bottom: 10px; }
        .checkbox-item input { width: auto; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Assigner un travail</h1>
        </div>
        
        <a href="/teacher/dashboard" class="back-link">← Retour au tableau de bord</a>  
        
        <div class="form-card">
            <?php if (isset($_SESSION['success'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    ✅ <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    ❌ <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if (!$selectedClass): ?>
                <form method="GET" action="/teacher/assignwork">
                    <div class="form-group">
                        <label for="class_id">Choisir une classe</label>
                        <select id="class_id" name="class_id" required onchange="this.form.submit()">
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
                    <div class="form-group">
                        <label>Classe sélectionnée</label>
                        <div style="background: #e9ecef; padding: 10px; border-radius: 5px;">
                            <?= htmlspecialchars($classes[array_search($selectedClass, array_column($classes, 'id'))]['name']) ?>
                            <a href="/teacher/assignwork" style="float: right; color: #007bff; text-decoration: none;">Changer</a>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="work_id">Travail à assigner</label>
                        <select id="work_id" name="work_id" required>
                            <option value="">-- Choisir un travail --</option>
                            <?php if ($works): ?>
                                <?php foreach ($works as $work): ?>
                                    <option value="<?= $work['id'] ?>"><?= htmlspecialchars($work['title']) ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="">Aucun travail disponible</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Étudiants</label>
                        <?php if ($students): ?>
                            <div class="checkbox-group">
                                <?php foreach ($students as $student): ?>
                                    <div class="checkbox-item">
                                        <input type="checkbox" name="student_ids[]" value="<?= $student['id'] ?>" id="student_<?= $student['id'] ?>">
                                        <label for="student_<?= $student['id'] ?>" style="margin-bottom: 0; font-weight: normal;">
                                            <?= htmlspecialchars($student['name']) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="color: #6c757d;">Aucun étudiant dans cette classe</p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($students && $works): ?>
                        <button type="submit" class="btn">Assigner le travail</button>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
