<?php
require_once __DIR__ . '/../models/Admodel.php';
session_start();
$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? null;
$ownerId = $user['id'] ?? null;

if (!$ownerId || $role !== 'proprietaire') {
    header('Location: /views/login.html');
    exit;
}

$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 9;

$result = get_owner_ads($ownerId, $page, $perPage);
$ads = $result['ads'];
$totalPages = $result['totalPages'];
$currentPage = $result['currentPage'];
$totalAds = $result['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Annonces - Home4Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f7fdf9; }
        .pagination {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            background: white;
            color: #2d3748;
            text-decoration: none;
            transition: all 0.2s;
        }
        .pagination a:hover {
            background: #e2e8f0;
        }
        .pagination .active {
            background: #48bb78;
            color: white;
        }
        .pagination .disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="min-h-screen bg-green-50">
    <header class="bg-green-200 py-3 shadow-sm">
        <div class="container mx-auto flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <img src="/views/logo-removebg-preview.png" alt="logo" class="h-10 w-10 rounded-full bg-white shadow">
                <span class="text-2xl font-bold text-green-900">Home4Student</span>
            </div>
            <div class="flex items-center gap-4">
                <h1 class="text-xl font-semibold text-green-900">Mes annonces</h1>
                <span class="bg-green-500 text-white px-3 py-1 rounded-full text-sm">
                    <i class="fas fa-home"></i> <?php echo $totalAds; ?> annonces
                </span>
            </div>
            <div class="flex gap-3">
                <a href="/views/search.html" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition flex items-center gap-2"><i class="fas fa-home"></i> Voir les offres</a>
                <a href="/views/index2.php" class="bg-gray-200 hover:bg-green-300 text-green-900 font-bold py-2 px-4 rounded transition flex items-center gap-2"><i class="fas fa-arrow-left"></i> Page d'accueil</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4">
        <!-- 新房源表单 -->
        <form id="add-ad-form" action="/views/ajouter_annonce.php" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 mt-8 mb-8">
            <h2 class="text-lg font-bold mb-4">Publier une nouvelle annonce</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="titre" placeholder="Titre" required class="border p-2 rounded">
                <input type="number" name="prix" placeholder="Prix (€)" required class="border p-2 rounded">
                <input type="text" name="rue" placeholder="Rue" required class="border p-2 rounded">
                <input type="text" name="ville" placeholder="Ville" required class="border p-2 rounded">
                <input type="text" name="codepostal" placeholder="Code Postal" required class="border p-2 rounded">
                <input type="text" name="type" placeholder="Type (ex: Studio, T2...)" required class="border p-2 rounded">
                <input type="file" name="image" required class="border p-2 rounded">
            </div>
            <textarea name="description" placeholder="Description" required class="border p-2 rounded mt-4 w-full"></textarea>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mt-4">Publier</button>
        </form>

        <!-- 房源卡片展示 -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            <?php
            if (empty($ads)) {
                echo '<div class="no-list text-center text-gray-400 col-span-3">Aucune annonce pour le moment.</div>';
            } else {
                foreach ($ads as $ad) {
                    $photos = ad_photos($ad['IdAnnonce']);
                    $photo = $photos ? $photos[0] : 'https://picsum.photos/800/600?grayscale&random=1';
                    echo '<div class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between min-h-[180px]">';
                    echo '<img src="/'.htmlspecialchars($photo).'" alt="'.htmlspecialchars($ad['Titre']).'" class="w-full h-44 object-cover rounded-xl mb-4">';
                    echo '<div class="font-bold text-lg mb-1">'.htmlspecialchars($ad['Titre']).'</div>';
                    echo '<div class="text-green-700 text-sm mb-1">'.htmlspecialchars($ad['rue']).' ・ '.htmlspecialchars($ad['codepostal']).' '.htmlspecialchars($ad['ville']).'</div>';
                    echo '<div class="text-gray-500 text-sm mb-1">'.htmlspecialchars($ad['Type']).' ・ '.htmlspecialchars($ad['Prix']).'€</div>';
                    echo '<div class="text-gray-700 text-sm mb-2">'.htmlspecialchars($ad['Descriptions']).'</div>';
                    echo '<div class="flex gap-2 mt-auto">';
                    echo '<a href="/views/ad.php?id='.$ad['IdAnnonce'].'" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition flex items-center gap-2"><i class="fas fa-eye"></i> Voir détails</a>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>

        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?php echo $currentPage - 1; ?>" class="page-link">
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php else: ?>
                <span class="disabled">
                    <i class="fas fa-chevron-left"></i>
                </span>
            <?php endif; ?>

            <?php
            $start = max(1, $currentPage - 2);
            $end = min($totalPages, $currentPage + 2);

            if ($start > 1) {
                echo '<a href="?page=1">1</a>';
                if ($start > 2) {
                    echo '<span class="disabled">...</span>';
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($i == $currentPage) {
                    echo '<span class="active">' . $i . '</span>';
                } else {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
            }

            if ($end < $totalPages) {
                if ($end < $totalPages - 1) {
                    echo '<span class="disabled">...</span>';
                }
                echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
            }
            ?>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?php echo $currentPage + 1; ?>" class="page-link">
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php else: ?>
                <span class="disabled">
                    <i class="fas fa-chevron-right"></i>
                </span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </main>
</body>
</html> 