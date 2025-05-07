<?php
// Affiche les erreurs si besoin
ini_set('display_errors', 1);
error_reporting(E_ALL);

$pdo = new PDO('mysql:host=localhost;dbname=home4student', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['question'])) {
    $question = trim($_POST['question']);

    $stmt = $pdo->prepare("INSERT INTO faq (question) VALUES (:question)");
    $stmt->execute([':question' => $question]);

    // Réponse visible dans .then(data => ...)
    echo "Question enregistrée avec succès !";
    exit;
} else {
    echo "Erreur : aucune question reçue.";
    exit;
}
