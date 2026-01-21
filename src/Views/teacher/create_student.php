<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Créer un étudiant</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .header { background: #28a745; color: white; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .form-card { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #333; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; }
        input:focus, select:focus { border-color: #28a745; outline: none; }
        .btn { background: #28a745; color: white; padding: 12px 24px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer; }
        .btn:hover { background: #218838; }
        .back-link { color: #28a745; text-decoration: none; margin-bottom: 20px; display: inline-block; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>👥 Créer un étudiant</h1>
            
        </div>
        <a href="/teacher/dashboard" class="back-link">← Retour au tableau de bord</a>  
        
        <div class="form-card">
            <?php if (isset($_SESSION['success'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    ✅ <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <form method="POST" action="/teacher/students/store">
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" required placeholder="Ex: Jean Dupont">
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="ex: etudiant@example.com">
                </div>
                
                <div class="form-group">
                    <label for="class_id">Classe</label>
                    <select id="class_id" name="class_id" required>
                        <option value="">-- Choisir une classe --</option>
                        <?php if ($classes): ?>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">Aucune classe disponible</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn">Créer l'étudiant</button>
            </form>
        </div>
    </div>
</body>
</html>
