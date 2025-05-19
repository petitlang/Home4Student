<?php
require_once __DIR__ . '/connection.php';

function add_signalement($type, $message, $idEtudiant, $idAnnonce) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO signaler (Type, message, IdEtudiant, IdAnnonce) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$type, $message, $idEtudiant, $idAnnonce]);
} 