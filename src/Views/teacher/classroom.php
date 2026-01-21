<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Classes</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: #007bff; color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .class-card { background: white; padding: 20px; margin: 10px 0; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .btn { background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
        .btn:hover { background: #218838; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn-primary { background: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-primary:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📚 Mes Classes</h1>
            <a href="/teacher/dashboard" class="btn">← Retour</a>
        </div>
        
        <form method="POST" action="/teacher/classrooms/store">
            <div class="class-card">
                <h3>Créer une nouvelle classe</h3>
                <div class="form-group">
                    <label>Nom de la classe:</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <button type="submit" class="btn-primary">Créer la classe</button>
            </div>
        </form>
        
        <?php if ($classes): ?>
            <?php foreach ($classes as $class): ?>
                <div class="class-card">
                    <h3><?= htmlspecialchars($class['name']) ?></h3>
                    <p><?= htmlspecialchars($class['description'] ?? '') ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="class-card">
                <p>Aucune classe créée pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
