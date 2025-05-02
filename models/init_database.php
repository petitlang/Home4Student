<?php
// =========================
// Vérifie si la base existe ; sinon, elle est créée avec les tables
// =========================

require_once __DIR__ . '/connection.php';
try {
    // Connexion au serveur MySQL (sans spécifier de base de données)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (file_exists($sqlFilePath)) {
        $sql = file_get_contents($sqlFilePath);
        $pdo->exec($sql);
        
    }
/*
    // Vérifie si la base de données existe déjà
    $stmt = $pdo->query("SHOW DATABASES LIKE '$dbName'");
    if ($stmt->rowCount() === 0) {
        // Si non, on la crée
        // Chargement et exécution du script SQL pour créer les tables
        if (file_exists($sqlFilePath)) {
            $sql = file_get_contents($sqlFilePath);
            $pdo->exec($sql);
            echo "✅ Base de données et tables créées avec succès.<br>";
        } else {
            echo "⚠️ Le fichier SQL '$sqlFilePath' est introuvable.<br>";
        }
    } else {
        // echo "✅ La base de données existe déjà.<br>";
        $pdo->exec("USE $dbname;");
    }
*/
} catch (PDOException $e) {
    die("❌ Erreur lors de l'initialisation de la base : " . $e->getMessage());
}

?>