<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes travaux - Étudiant</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #28a745; color: white; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .welcome { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .card-title { color: #333; font-size: 20px; margin-bottom: 15px; }
        .card-info { color: #666; margin-bottom: 15px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; min-height: 120px; resize: vertical; font-family: Arial, sans-serif; }
        .btn { background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn:hover { background: #218838; }
        .btn:disabled { background: #6c757d; cursor: not-allowed; }
        .back-link { color: #28a745; text-decoration: none; margin-bottom: 20px; display: inline-block; font-weight: bold; }
        .logout { float: right; background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; }
        .logout:hover { background: #c82333; }
        .submitted { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 15px; }
        .deadline { font-weight: bold; color: #dc3545; }
        .classroom { background: #e9ecef; padding: 5px 10px; border-radius: 15px; font-size: 12px; color: #495057; display: inline-block; margin-bottom: 5px; }
        .submission-content { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; white-space: pre-wrap; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>📝 Mes travaux</h1>
            <a href="/logout" class="logout">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <a href="/student/dashboard" class="back-link">← Retour au tableau de bord</a>
        
        <div class="welcome">
            <h2>Mes travaux à faire</h2>
            <p>Consultez et soumettez vos travaux assignés.</p>
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
        
        <?php if ($works): ?>
            <?php foreach ($works as $work): ?>
                <div class="card">
                    <h3 class="card-title"><?= htmlspecialchars($work['title']) ?></h3>
                    
                    <div class="card-info">
                        <span class="classroom"><?= htmlspecialchars($work['classroom_name']) ?></span>
                        <div style="margin-top: 10px;">
                            <strong>Date limite:</strong> 
                            <span class="deadline"><?= date('d/m/Y', strtotime($work['deadline'])) ?></span>
                        </div>
                        
                        <?php if ($work['description']): ?>
                            <div style="margin-top: 10px;">
                                <strong>Description:</strong><br>
                                <?= nl2br(htmlspecialchars($work['description'])) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- État de la soumission -->
                    <?php if ($work['submission_id']): ?>
                        <div class="submitted">
                            ✅ Soumis le <?= date('d/m/Y à H:i', strtotime($work['submitted_at'])) ?>
                            <div style="margin-top: 5px; color: #6c757d; font-size: 14px;">
                                En attente de correction
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Formulaire de soumission -->
                        <form method="POST" action="/student/submissions" style="margin-top: 20px;">
                            <input type="hidden" name="work_id" value="<?= $work['id'] ?>">
                            
                            <div class="form-group">
                                <label for="content_<?= $work['id'] ?>">Votre réponse:</label>
                                <textarea name="content" id="content_<?= $work['id'] ?>" required 
                                          placeholder="Écrivez votre réponse ici..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn">Soumettre le travail</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="card">
                <p>Vous n'avez aucun travail assigné pour le moment.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
