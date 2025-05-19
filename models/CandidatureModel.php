<?php
require_once __DIR__ . '/connection.php'; // $pdo

function add_candidature($id_etudiant, $id_annonce, $debut, $fin) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO candidature (IdEtudiant, IdAnnonce, debut, fin) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$id_etudiant, $id_annonce, $debut, $fin]);
}

function get_candidatures_by_etudiant($id_etudiant) {
    global $pdo;
    $sql = "SELECT c.*, a.Titre, a.ville, a.Type, a.Prix, a.Descriptions
            FROM candidature c
            JOIN annonce a ON c.IdAnnonce = a.IdAnnonce
            WHERE c.IdEtudiant = ?
            ORDER BY c.debut DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_etudiant]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function delete_candidature($id_candidature) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM candidature WHERE IdCandidature = ?");
    return $stmt->execute([$id_candidature]);
} 