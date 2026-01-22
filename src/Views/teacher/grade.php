<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noter les travaux - Enseignant</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #007bff; color: white; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .welcome { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-title { color: #333; font-size: 20px; margin-bottom: 15px; }
        .card-info { color: #666; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="number"], input[type="text"] { padding: 10px; border: 1px solid #ddd; border-radius: 5px; margin-right: 10px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .back-link { color: #007bff; text-decoration: none; margin-bottom: 20px; display: inline-block; font-weight: bold; }
        .logout { float: right; background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; }
        .logout:hover { background: #c82333; }
        .graded { background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 10px; }
        .submission-content { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>📊 Noter les travaux</h1>
            <a href="/logout" class="logout">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <a href="/teacher/dashboard" class="back-link">← Retour au tableau de bord</a>
        
        <div class="welcome">
            <h2>Travaux assignés aux étudiants</h2>
            <p>Vue d'ensemble des travaux assignés et soumissions à noter.</p>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                ✅ <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                ❌ <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <?php if ($assignedWorks): ?>
            <?php foreach ($assignedWorks as $work): ?>
                <div class="card">
                    <h3 class="card-title"><?= htmlspecialchars($work['title']) ?></h3>
                    <div class="card-info">
                        <p><strong>Classe:</strong> <?= htmlspecialchars($work['classroom_name']) ?></p>
                        <p><strong>Étudiants assignés:</strong> <?= $work['assigned_students'] ?></p>
                        <p><strong>Date limite:</strong> <?= date('d/m/Y', strtotime($work['deadline'])) ?></p>
                        
                        <?php if ($work['description']): ?>
                            <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($work['description'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card">
                <p>Aucun travail assigné pour le moment.</p>
            </div>
        <?php endif; ?>

        <hr style="margin: 30px 0;">

        <div class="welcome">
            <h3>Soumissions à noter</h3>
            <p>Travaux soumis par les étudiants en attente de notation.</p>
        </div>

        <?php 
        // Afficher les soumissions existantes pour noter
        $model = new \App\Models\Grade();
        $submissions = $model->getSubmissions();
        ?>

        <?php if ($submissions): ?>
            <?php foreach ($submissions as $s): ?>
                <div class="card">
                    <h4 class="card-title"><?= htmlspecialchars($s['name']) ?></h4>
                    
                    <div class="submission-content">
                        <?= htmlspecialchars($s['content']) ?>
                    </div>

                    <form method="POST" action="/teacher/grade" style="margin-top: 15px;">
                        <input type="hidden" name="submission_id" value="<?= $s['id'] ?>">
                        
                        <div class="form-group">
                            <label for="score_<?= $s['id'] ?>">Note (0-20):</label>
                            <input type="number" id="score_<?= $s['id'] ?>" name="score" min="0" max="20" required placeholder="Note/20">
                        </div>
                        
                        <div class="form-group">
                            <label for="comment_<?= $s['id'] ?>">Commentaire:</label>
                            <input type="text" id="comment_<?= $s['id'] ?>" name="comment" placeholder="Commentaire sur le travail">
                        </div>
                        
                        <button type="submit" class="btn">Noter le travail</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card">
                <p>Aucune soumission à noter pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
