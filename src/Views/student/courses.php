<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes cours - Étudiant</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #28a745; color: white; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .welcome { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .course-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .course-title { color: #333; font-size: 20px; margin-bottom: 10px; }
        .course-info { color: #666; margin-bottom: 15px; }
        .back-link { color: #28a745; text-decoration: none; margin-bottom: 20px; display: inline-block; font-weight: bold; }
        .logout { float: right; background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; }
        .logout:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>📚 Mes cours</h1>
            <a href="/logout" class="logout">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <a href="/student/dashboard" class="back-link">← Retour au tableau de bord</a>
        
        <div class="welcome">
            <h2>Vos cours</h2>
            <p>Voici la liste des cours auxquels vous êtes inscrit.</p>
        </div>
        
        <?php if ($classrooms): ?>
            <?php foreach ($classrooms as $classroom): ?>
                <div class="course-card">
                    <h3 class="course-title"><?= htmlspecialchars($classroom['name']) ?></h3>
                    <div class="course-info">
                        <?php if ($classroom['description']): ?>
                            <p><?= htmlspecialchars($classroom['description']) ?></p>
                        <?php else: ?>
                            <p>Aucune description disponible pour ce cours.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="course-card">
                <p>Vous n'êtes inscrit à aucun cours pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
