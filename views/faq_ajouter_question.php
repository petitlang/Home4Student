<?php
session_start();

// Vérifie que l'utilisateur est connecté et qu'il est admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Accès refusé.");
}

$pdo = new PDO('mysql:host=localhost;dbname=home4student', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) $_POST['id'];
    $reponse = trim($_POST['reponse']);

    
    $stmt = $pdo->prepare("UPDATE faq SET reponse = :reponse WHERE IdFAQ = :id");
    $stmt->execute([
        ':reponse' => $reponse,
        ':id' => $id
    ]);

    header('Location: faq_back.php');
    exit;
}
?>
