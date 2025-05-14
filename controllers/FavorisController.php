<?php
require_once __DIR__ . '/../models/FavorisModel.php';

session_start();

$user = $_SESSION['user'] ?? null;
if (!$user) {
    header('Location: /views/login.html');
    exit;
}
$userId = $user['id'];
$action = $_POST['action'] ?? '';
$annonceId = isset($_POST['id_annonce']) ? (int)$_POST['id_annonce'] : 0;

if (!$annonceId) {
    header('Location: /views/ads_list.php');
    exit;
}

switch ($action) {
    case 'add':
        error_log("FavorisController: add action, userId=$userId, annonceId=$annonceId");
        if (add_favoris($userId, $annonceId)) {
            $_SESSION['success_message'] = "Annonce ajoutée aux favoris avec succès.";
            header('Location: /views/favoris.php');
            exit;
        } else {
            $_SESSION['error_message'] = "Erreur lors de l'ajout aux favoris.";
            error_log("FavorisController: add_favoris failed for userId=$userId, annonceId=$annonceId");
        }
        break;
        
    case 'remove':
        if (remove_favoris($userId, $annonceId)) {
            $_SESSION['success_message'] = "Annonce retirée des favoris avec succès.";
        } else {
            $_SESSION['error_message'] = "Erreur lors du retrait des favoris.";
        }
        break;
        
    default:
        $_SESSION['error_message'] = "Action non valide.";
        break;
}

// 重定向回之前的页面
$redirect = $_SERVER['HTTP_REFERER'] ?? '/views/ads_list.php';
header("Location: $redirect");
exit; 