<?php
// ────────────────────────────────────────────────────────────────
// DeleteAdController.php — Contrôleur pour la suppression d'annonces
// ────────────────────────────────────────────────────────────────

session_start();
require_once __DIR__ . '/../models/Admodel.php';
require_once __DIR__ . '/../models/connection.php';

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée.";
    header('Location: /views/search.php');
    exit();
}

// Vérification de la session et des permissions
if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    $_SESSION['error_message'] = "Veuillez vous connecter pour effectuer cette action.";
    header('Location: /views/login.php');
    exit();
}

// Vérification du rôle
if (!in_array($_SESSION['role'], ['admin', 'proprietaire'])) {
    $_SESSION['error_message'] = "Vous n'avez pas les droits pour effectuer cette opération.";
    header('Location: /views/search.php');
    exit();
}

// Récupération et validation de l'ID de l'annonce
$id_annonce = filter_input(INPUT_POST, 'id_annonce', FILTER_VALIDATE_INT);
if (!$id_annonce) {
    $_SESSION['error_message'] = "ID d'annonce invalide.";
    header('Location: /views/search.php');
    exit();
}

try {
    // Récupération des informations de l'annonce
    $ad = ad_get($id_annonce);
    if (!$ad) {
        $_SESSION['error_message'] = "L'annonce n'existe pas.";
        header('Location: /views/search.php');
        exit();
    }

    // Vérification des droits de suppression
    if ($_SESSION['role'] !== 'admin' && $ad['IdProprietaire'] !== $_SESSION['user']['id']) {
        $_SESSION['error_message'] = "Vous n'avez pas les droits pour supprimer cette annonce.";
        header('Location: /views/search.php');
        exit();
    }

    // Début de la transaction
    $pdo->beginTransaction();

    // Suppression des données associées dans l'ordre
    $tables = [
        'PhotoAnnonce' => 'IdAnnonce',
        'favoris' => 'IdAnnonce',
        'candidature' => 'IdAnnonce',
        'signaler' => 'IdAnnonce',
        'annonce' => 'IdAnnonce'
    ];

    foreach ($tables as $table => $column) {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE $column = ?");
        if (!$stmt->execute([$id_annonce])) {
            throw new Exception("Erreur lors de la suppression des données de la table $table");
        }
    }

    // Validation de la transaction
    $pdo->commit();
    $_SESSION['success_message'] = "L'annonce a été supprimée avec succès.";

} catch (Exception $e) {
    // En cas d'erreur, annulation de la transaction
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Erreur de suppression d'annonce: " . $e->getMessage());
    $_SESSION['error_message'] = "Une erreur est survenue lors de la suppression de l'annonce.";
}

// Redirection
header('Location: /views/search.php');
exit(); 