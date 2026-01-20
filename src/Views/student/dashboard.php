<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Étudiant</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #28a745; color: white; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .welcome { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card h3 { color: #333; margin-bottom: 15px; }
        .logout { float: right; background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; }
        .logout:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🎓 Minerva - Espace Étudiant</h1>
            <a href="/logout" class="logout">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h2>Bonjour <?= htmlspecialchars($user['name']) ?> ! 👋</h2>
            <p>Bienvenue sur votre tableau de bord. Vous êtes connecté en tant qu'étudiant.</p>
        </div>
        
        <div class="cards">
            <div class="card">
                <h3>📚 Mes cours</h3>
                <p>Consultez vos cours et matières.</p>
            </div>
            <div class="card">
                <h3>📝 Devoirs à rendre</h3>
                <p>Voir les devoirs en cours.</p>
            </div>
            <div class="card">
                <h3>📊 Mes notes</h3>
                <p>Consultez vos résultats.</p>
            </div>
        </div>
    </div>
</body>
</html>