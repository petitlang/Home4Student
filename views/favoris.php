<?php
require_once __DIR__ . '/../models/Admodel.php';
require_once __DIR__ . '/../models/FavorisModel.php';

// 获取当前登录用户ID
session_start();
$user = $_SESSION['user'] ?? null;
$userId = $user['id'] ?? null;
$role = $_SESSION['role'] ?? 'etudiant';

if (!$userId) {
    // 保存当前页面URL到session，以便登录后重定向回来
    if (isset($_GET['redirect'])) {
        $_SESSION['redirect_after_login'] = $_GET['redirect'];
    }
    header('Location: /views/login.php');
    exit;
}

// 获取当前页码
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$perPage = 9;

// 获取用户收藏的房屋列表（带分页）
$result = get_user_favoris($userId, $page, $perPage, $role);
$favoris = $result['favoris'];
$totalPages = $result['totalPages'];
$currentPage = $result['currentPage'];
$totalFavoris = $result['total'];

// 获取收藏总数
$favorisCount = get_favoris_count($userId, $role);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Favoris - Home4Student</title>
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
    <?php include __DIR__ . '/header.php'; ?>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['success_message']; ?></span>
                <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <span class="sr-only">Fermer</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo $_SESSION['error_message']; ?></span>
                <button class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <span class="sr-only">Fermer</span>
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <main class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-8">
            <?php
            if (empty($favoris)) {
                echo '<div class="no-list text-center text-gray-400 col-span-3">Aucun favori pour le moment.</div>';
            } else {
                foreach ($favoris as $favori) {
                    $photos = ad_photos($favori['IdAnnonce']);
                    $photo = $photos ? $photos[0] : 'https://picsum.photos/800/600?grayscale&random=1';
                    echo '<div class="bg-white rounded-2xl shadow p-6 flex flex-col justify-between min-h-[180px]">';
                    echo '<img src="/'.htmlspecialchars($photo).'" alt="'.htmlspecialchars($favori['Titre']).'" class="w-full h-44 object-cover rounded-xl mb-4">';
                    echo '<div class="font-bold text-lg mb-1">'.htmlspecialchars($favori['Titre']).'</div>';
                    echo '<div class="text-green-700 text-sm mb-1">'.htmlspecialchars($favori['rue']).' ・ '.htmlspecialchars($favori['codepostal']).' '.htmlspecialchars($favori['ville']).'</div>';
                    echo '<div class="text-gray-500 text-sm mb-1">'.htmlspecialchars($favori['Type']).' ・ '.htmlspecialchars($favori['Prix']).'€</div>';
                    echo '<div class="text-gray-700 text-sm mb-2">'.htmlspecialchars($favori['Descriptions']).'</div>';
                    echo '<div class="flex gap-2 mt-auto">';
                    echo '<a href="/views/ad.php?id='.$favori['IdAnnonce'].'" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition flex items-center gap-2"><i class="fas fa-eye"></i> Voir détails</a>';
                    echo '<form method="post" action="/controllers/FavorisController.php" style="display:inline;">';
                    echo '<input type="hidden" name="action" value="remove">';
                    echo '<input type="hidden" name="id_annonce" value="'.$favori['IdAnnonce'].'">';
                    echo '<button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition flex items-center gap-2"><i class="fas fa-heart-broken"></i> Retirer</button>';
                    echo '</form>';
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
    <?php include __DIR__ . '/footer.html'; ?>
</body>
</html> 