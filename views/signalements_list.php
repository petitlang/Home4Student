<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /views/login.html');
    exit();
}
require_once __DIR__ . '/../models/connection.php';

// 查询所有举报信息，带status字段
$sql = "SELECT s.*, e.Prenom AS PrenomEtudiant, e.Nom AS NomEtudiant FROM signaler s LEFT JOIN etudiant e ON s.IdEtudiant = e.IdEtudiant ORDER BY s.IdSignaler DESC";
$stmt = $pdo->query($sql);
$signalements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des signalements</title>
    <link rel="stylesheet" href="/views/search.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="/views/logo-removebg-preview.png" alt="logo" />
            </div>
            <div class="nav-center">
                <div class="nav-links">
                    <a href="/views/ads_list.php">Offres</a>
                    <a href="/views/chat.php">Messagerie</a>
                    <a href="/views/faq_back.html">FAQ</a>
                    <a href="/views/contact.html">Contact</a>
                    <a href="/views/cgu.html">CGU</a>
                </div>
            </div>
            <div class="nav-buttons">
                <a href="/views/index2.php" class="btn-solid">Page d'accueil</a>
            </div>
        </nav>
    </header>
    <div class="container search-bg">
        <h1>Tous les signalements</h1>
        <div class="filters">
            <input type="text" id="search" placeholder="Rechercher un signalement...">
            <select id="typeFilter">
                <option value="">Tous les types</option>
                <option value="Information fausse">Information fausse</option>
                <option value="Harcèlement">Harcèlement</option>
                <option value="Autre">Autre</option>
            </select>
            <select id="statusFilter">
                <option value="">Tous les statuts</option>
                <option value="Non traité">Non traité</option>
                <option value="Traité">Traité</option>
            </select>
        </div>
        <div id="cards" class="cards"></div>
    </div>
    <script>
        const allSignalements = <?php echo json_encode($signalements, JSON_UNESCAPED_UNICODE); ?>;
        const cardsContainer = document.getElementById('cards');
        const searchInput = document.getElementById('search');
        const typeFilter = document.getElementById('typeFilter');
        const statusFilter = document.getElementById('statusFilter');

        function renderCards() {
            const search = searchInput.value.toLowerCase();
            const type = typeFilter.value;
            const status = statusFilter.value;
            const filtered = allSignalements.filter(item => {
                const matchType = !type || item.Type === type;
                const matchText = item.message.toLowerCase().includes(search);
                const matchStatus = !status || (item.status || 'Non traité') === status;
                return matchType && matchText && matchStatus;
            });

            cardsContainer.innerHTML = '';
            filtered.forEach(card => {
                const div = document.createElement('div');
                div.className = 'card';
                div.onclick = () => window.location.href = '/views/signalement.php?id=' + card.IdSignaler;
                div.innerHTML = `
                    <h3>Type : ${card.Type}</h3>
                    <p>Contenu : ${card.message.length > 60 ? card.message.substr(0, 60) + '...' : card.message}</p>
                    <p>Signaleur : ${(card.PrenomEtudiant || '') + ' ' + (card.NomEtudiant || '')} (ID: ${card.IdEtudiant})</p>
                    <p>ID de l'annonce : <a href="/views/ad.php?id=${card.IdAnnonce}" target="_blank" onclick="event.stopPropagation();">${card.IdAnnonce}</a></p>
                    <p>Numéro de signalement : ${card.IdSignaler}</p>
                    <p>Statut : ${card.status || 'Non traité'}</p>
                `;
                cardsContainer.appendChild(div);
            });
            if (filtered.length === 0) {
                cardsContainer.innerHTML = '<div style="color:#888;text-align:center;grid-column:1/-1;">Aucun signalement</div>';
            }
        }

        searchInput.addEventListener('input', renderCards);
        typeFilter.addEventListener('change', renderCards);
        statusFilter.addEventListener('change', renderCards);

        renderCards();
    </script>
</body>
</html> 