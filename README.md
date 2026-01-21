# 🎓 Minerva - School Management System

Projet pédagogique PHP MVC simple pour la gestion scolaire.

## 📋 Prérequis

- XAMPP (Apache + MySQL)
- PHP 8+
- Composer

## 🚀 Installation

1. **Démarrer XAMPP** : Lance Apache et MySQL

2. **Configurer la base de données** :
   - Ouvre phpMyAdmin
   - Importe le fichier `sql/minerva.sql`

3. **Installer les dépendances** :
   ```bash
   composer install
   ```

4. **Créer les comptes de test** :
   - Exécute le script `test_setup.php` une fois
   - Ou ajoute manuellement des utilisateurs dans la BDD

## 🔐 Comptes de test

- **Enseignant** : `prof@minerva.com` / `password123`
- **Étudiant** : `etudiant@minerva.com` / `password123`

## 🌐 Accès

Ouvre `http://localhost/Minerva-` dans ton navigateur

## 📁 Structure du projet

```
Minerva-/
├── public/
│   └── index.php          # Point d'entrée
├── src/
│   ├── Controllers/       # Contrôleurs
│   ├── Core/             # Cœur du framework
│   ├── Models/           # Modèles de données
│   ├── Services/        # Logique métier
│   └── Views/            # Vues HTML
├── config/
│   └── database.php     # Configuration BDD
├── sql/
│   └── minerva.sql      # Structure BDD
└── .htaccess            # Réécriture d'URL
```

## 🎯 Architecture MVC

- **Modèles** : Accès aux données (User.php)
- **Vues** : Interface utilisateur (HTML)
- **Contrôleurs** : Logique de navigation
- **Services** : Logique métier (AuthService)

## 🔧 Fonctionnalités

- ✅ Authentification sécurisée
- ✅ Rôles (teacher/student)
- ✅ Dashboard selon le rôle
- ✅ Protection des routes
- ✅ Sessions PHP

## 📚 Pour apprendre

1. **Commence par** `public/index.php` : le point d'entrée
2. **Regarde** `src/Controllers/AuthController.php` : comment gérer le login
3. **Étudie** `src/Services/AuthService.php` : la logique métier
4. **Comprends** `src/Core/Router.php` : comment les URLs fonctionnent

## 🎓 C'est un projet pédagogique !

Le code est simplifié pour être facile à comprendre.
Pas de framework complexe, juste du PHP pur avec une architecture MVC claire.