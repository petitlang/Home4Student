<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'etudiant') {
    header('Location: /views/login.php');
    exit();
}
$user = $_SESSION['user'];
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
        <div class="card-list">
            <?php
            // 示例数据，后续用数据库替换
            $rooms = [
                [
                    'img' => '/views/acc.jpg',
                    'title' => 'Chambre proche université',
                    'desc' => '15m², tout équipé, 5min à pied du campus',
                    'status' => 'Réservé'
                ],
                [
                    'img' => '/views/acc2.jpg',
                    'title' => 'Studio centre-ville',
                    'desc' => '20m², lumineux, proche transports',
                    'status' => 'En attente'
                ]
            ];
            if (empty($rooms)) {
                echo '<div class="no-list">Aucune réservation pour le moment.</div>';
            } else {
                foreach ($rooms as $room) {
                    echo '<div class="room-card">';
                    echo '<img class="room-img" src="'.htmlspecialchars($room['img']).'" alt="room">';
                    echo '<div class="room-content">';
                    echo '<div class="room-title">'.htmlspecialchars($room['title']).'</div>';
                    echo '<div class="room-desc">'.htmlspecialchars($room['desc']).'</div>';
                    echo '<div class="room-status"><i class="fas fa-check-circle"></i> '.htmlspecialchars($room['status']).'</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </main>
</body>
</html> 