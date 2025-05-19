<?php
require_once __DIR__ . '/../models/SignalementModel.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $message = $_POST['message'] ?? '';
    $idEtudiant = $_SESSION['user']['id'] ?? null;
    $idAnnonce = $_POST['id_annonce'] ?? null;

    if ($type && $message && $idEtudiant && $idAnnonce) {
        if (add_signalement($type, $message, $idEtudiant, $idAnnonce)) {
            $_SESSION['success_message'] = 'Signalement soumis avec succès !';
        } else {
            $_SESSION['error_message'] = 'Échec de la soumission du signalement, veuillez réessayer.';
        }
    } else {
        $_SESSION['error_message'] = 'Veuillez remplir tous les champs.';
    }
    header('Location: /views/ad.php?id=' . urlencode($idAnnonce));
    exit;
}
// Non-POST : redirection
header('Location: /views/ads_list.php');
exit; 