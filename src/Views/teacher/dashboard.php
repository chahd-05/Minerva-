<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Enseignant</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .header { background: #007bff; color: white; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .welcome { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 30px; }
        .cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .card h3 { color: #333; margin-bottom: 15px; }
        .card a { text-decoration: none; color: #333; }
        .card a:hover h3 { color: #007bff; }
        .logout { float: right; background: #dc3545; color: white; padding: 8px 16px; text-decoration: none; border-radius: 5px; }
        .logout:hover { background: #c82333; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🎓 Minerva - Espace Enseignant</h1>
            <a href="/logout" class="logout">Déconnexion</a>
        </div>
    </div>
    
    <div class="container">
        <div class="welcome">
            <h2>Bonjour <?= htmlspecialchars($user['name']) ?> ! 👋</h2>
            <p>Bienvenue sur votre tableau de bord. Vous êtes connecté en tant qu'enseignant.</p>
        </div>
        
        <div class="cards">
            <div class="card">
                <a href="/teacher/classrooms"><h3>📚 Classes</h3></a>
                <p>Créez et suivez des classes.</p>
            </div>
            <div class="card">
                <a href="/teacher/students/create"><h3>👥 Étudiants</h3></a>
                <p>Créez des comptes étudiants.</p>
            </div>
            <div class="card">
                <a href="/teacher/creatework"><h3>📝 Créer un devoir</h3></a>
                <p>Créez de nouveaux devoirs pour vos classes.</p>
            </div>
            <div class="card">
                <a href="/teacher/assignwork"><h3>🎯 Assigner un devoir</h3></a>
                <p>Assignez les devoirs aux étudiants.</p>
            </div>
            <div class="card">
                <a href="/teacher/grade"><h3>📊 Noter les travaux</h3></a>
                <p>Notez les travaux soumis par vos étudiants.</p>
            </div>
        </div>
    </div>
</body>
</html>