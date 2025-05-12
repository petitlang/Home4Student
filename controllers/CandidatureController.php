<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
    header('Location: /views/login.html');
    exit();
}
require_once __DIR__ . '/../models/CandidatureModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_etudiant = $_SESSION['user']['id'];
    $id_annonce = $_POST['id_annonce'];
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    // 可加人数等
    add_candidature($id_etudiant, $id_annonce, $debut, $fin);
    header('Location: /views/candidature.php');
    exit();
} 