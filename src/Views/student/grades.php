<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes notes - Étudiant</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #28a745; color: white; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .welcome { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .grade-card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .grade-title { color: #333; font-size: 18px; margin-bottom: 10px; }
        .grade-score { font-size: 24px; font-weight: bold; color: #28a745; margin-bottom: 10px; }
        .grade-info { color: #666; margin-bottom: 10px; }
        .grade-comment { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px; }
        .back-link { color: #28a745; text-decoration: none; margin-bottom: 20px; display: inline-block; font-weight: bold; }
        .logout { float: right; background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; }
        .logout:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>📊 Mes notes</h1>
            <a href="/logout" class="logout">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <a href="/student/dashboard" class="back-link">← Retour au tableau de bord</a>
        
        <div class="welcome">
            <h2>Vos résultats</h2>
            <p>Voici l'historique de vos notes et commentaires.</p>
        </div>
        
        <?php if ($grades): ?>
            <?php foreach ($grades as $grade): ?>
                <div class="grade-card">
                    <h3 class="grade-title"><?= htmlspecialchars($grade['work_title']) ?></h3>
                    <div class="grade-info">
                        <p><strong>Cours:</strong> <?= htmlspecialchars($grade['classroom_name']) ?></p>
                        <p><strong>Soumis le:</strong> <?= date('d/m/Y à H:i', strtotime($grade['submitted_at'])) ?></p>
                    </div>
                    <div class="grade-score">
                        📊 Note: <?= $grade['score'] ?>/20
                    </div>
                    <?php if ($grade['comment']): ?>
                        <div class="grade-comment">
                            <strong>Commentaire du professeur:</strong><br>
                            <?= nl2br(htmlspecialchars($grade['comment'])) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="grade-card">
                <p>Vous n'avez aucune note pour le moment.</p>
                <p>Vos travaux soumis apparaîtront ici une fois notés par vos professeurs.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
