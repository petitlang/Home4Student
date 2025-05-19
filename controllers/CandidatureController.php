<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
    header('Location: /views/login.html');
    exit();
}
require_once __DIR__ . '/../models/CandidatureModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_candidature']) && isset($_POST['id_candidature'])) {
        // 删除candidature
        $id_candidature = $_POST['id_candidature'];
        delete_candidature($id_candidature);
        header('Location: /views/candidature.php');
        exit();
    }
    $id_etudiant = $_SESSION['user']['id'];
    $id_annonce = $_POST['id_annonce'];
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    // 可加人数等
    add_candidature($id_etudiant, $id_annonce, $debut, $fin);
    header('Location: /views/candidature.php');
    exit();
} 