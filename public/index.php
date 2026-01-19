<?php

require __DIR__ . '/../src/Core/Database.php';

try {
    $pdo = App\Core\Database::getPDO();
    echo "✓ Connexion à la base de données réussie!\n";
    
  
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
}
