<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
    header('Location: /views/login.php');
    exit();
}
require_once __DIR__ . '/../models/CandidatureModel.php';

$id_etudiant = $_SESSION['user']['id'];
$rooms = get_candidatures_by_etudiant($id_etudiant);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations - Home4Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f7fdf9; }
        .card-list { display: flex; flex-wrap: wrap; gap: 2rem; justify-content: center; margin-top: 2rem; }
        .room-card { background: #fff; border-radius: 18px; box-shadow: 0 4px 16px rgba(44,204,113,0.08); width: 320px; overflow: hidden; display: flex; flex-direction: column; }
        .room-img { width: 100%; height: 180px; object-fit: cover; background: #eaeaea; }
        .room-content { padding: 1.2rem; flex: 1; display: flex; flex-direction: column; }
        .room-title { font-size: 1.15rem; font-weight: 600; color: #222; margin-bottom: 0.5rem; }
        .room-desc { color: #666; font-size: 0.98rem; margin-bottom: 0.7rem; }
        .room-status { font-size: 0.95rem; color: #2ecc71; font-weight: 500; margin-top: auto; }
        .no-list { text-align: center; color: #aaa; margin: 3rem 0; font-size: 1.1rem; }
    </style>
</head>
<body class="min-h-screen bg-green-50">
    <header class="bg-green-200 py-3 shadow-sm">
        <div class="container mx-auto flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <img src="/views/logo-removebg-preview.png" alt="logo" class="h-10 w-10 rounded-full bg-white shadow">
                <span class="text-2xl font-bold text-green-900">Home4Student</span>
            </div>
            <h1 class="text-xl font-semibold text-green-900">Mes réservations</h1>
            <div class="flex gap-3">
                <a href="/views/search.html" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition flex items-center gap-2"><i class="fas fa-plus"></i> Réserver un logement</a>
                <a href="/views/index2.php" class="bg-gray-200 hover:bg-green-300 text-green-900 font-bold py-2 px-4 rounded transition flex items-center gap-2"><i class="fas fa-arrow-left"></i> Retour accueil</a>
            </div>
        </div>
    </header>
    <main class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            <?php
            if (empty($rooms)) {
                echo '<div class="no-list">Aucune réservation pour le moment.</div>';
            } else {
                foreach ($rooms as $room) {
                    echo '<div class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between min-h-[180px]">';
                    echo '<div class="mb-2">';
                    echo '<div class="font-bold text-lg mb-1">'.htmlspecialchars($room['Titre']).'</div>';
                    echo '<div class="text-gray-500 text-sm mb-1">'.htmlspecialchars($room['ville']).' ・ '.htmlspecialchars($room['Type']).' ・ '.htmlspecialchars($room['Prix']).'€</div>';
                    echo '<div class="text-gray-700 text-sm">'.htmlspecialchars($room['Descriptions']).'</div>';
                    echo '<div class="text-gray-500 text-xs mt-2">Du '.htmlspecialchars($room['debut']).' au '.htmlspecialchars($room['fin']).'</div>';
                    echo '</div>';
                    echo '<div class="flex gap-2 mt-auto">';
                    // Voir détails
                    echo '<a href="/views/ad.php?id='.htmlspecialchars($room['IdAnnonce']).'" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition flex items-center gap-2"><i class="fas fa-eye"></i> Voir détails</a>';
                    // Retirer
                    echo '<form method="post" action="/controllers/CandidatureController.php" style="display:inline;">';
                    echo '<input type="hidden" name="delete_candidature" value="1">';
                    echo '<input type="hidden" name="id_candidature" value="'.htmlspecialchars($room['IdCandidature']).'">';
                    echo '<button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition flex items-center gap-2"><i class="fas fa-heart-broken"></i> Retirer</button>';
                    echo '</form>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </main>
</body>
</html> 