<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

$id = $_GET['id'] ?? null; // 举报编号
$isAdmin = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
$isEtudiant = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'etudiant';

// 管理员/学生都能看，未登录跳转
if (!$isAdmin && !$isEtudiant) {
    header('Location: /views/login.html');
    exit;
}

// 管理员查看举报详情
if ($isAdmin && $id) {
    $stmt = $pdo->prepare("SELECT s.*, e.Prenom AS PrenomEtudiant, e.Nom AS NomEtudiant FROM signaler s LEFT JOIN etudiant e ON s.IdEtudiant = e.IdEtudiant WHERE s.IdSignaler = ?");
    $stmt->execute([$id]);
    $signalement = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$signalement) {
        die('Signalement inexistant');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Signalement</title>
    <link rel="stylesheet" href="/views/ad.css" />
    <style>
        .signalement-form { max-width: 600px; margin: 2rem auto; background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 2px 8px #ccc; }
        .signalement-form label { display: block; margin-bottom: 0.5rem; font-weight: bold; }
        .signalement-form select, .signalement-form textarea { width: 100%; margin-bottom: 1rem; padding: 0.5rem; border-radius: 5px; border: 1px solid #ccc; }
        .signalement-form button { background: #e53e3e; color: #fff; border: none; padding: 0.75rem 2rem; border-radius: 5px; font-size: 1rem; cursor: pointer; }
        .signalement-form button:hover { background: #c53030; }
        .admin-btns { display: flex; justify-content: flex-end; gap: 1.5rem; margin-top: 2rem; }
        .btn-green { background: #4CAF50 !important; }
        .btn-red { background: #e53e3e !important; }
    </style>
</head>
<body>
    <div class="signalement-form">
        <?php if ($isAdmin && $id): ?>
            <h2>Détail du signalement</h2>
            <p><b>Type :</b> <?= htmlspecialchars($signalement['Type']) ?></p>
            <p><b>Contenu :</b> <?= nl2br(htmlspecialchars($signalement['message'])) ?></p>
            <p><b>Signaleur :</b> <?= htmlspecialchars(($signalement['PrenomEtudiant'] ?? '') . ' ' . ($signalement['NomEtudiant'] ?? '')) ?> (ID: <?= htmlspecialchars($signalement['IdEtudiant']) ?>)</p>
            <p><b>ID de l'annonce :</b>
                <a href="/views/ad.php?id=<?= htmlspecialchars($signalement['IdAnnonce']) ?>" target="_blank" style="color:#3182ce; text-decoration:underline;">
                    <?= htmlspecialchars($signalement['IdAnnonce']) ?>
                </a>
            </p>
            <p><b>Numéro de signalement :</b> <?= htmlspecialchars($signalement['IdSignaler']) ?></p>
            <p><b>Statut :</b> <?= htmlspecialchars($signalement['status'] ?? 'Non traité') ?></p>
            <div class="admin-btns">
                <form method="post" action="/controllers/SignalementActionController.php" style="display:inline;">
                    <input type="hidden" name="action" value="mark_done">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                    <button type="submit" class="btn-green">Marquer comme traité</button>
                </form>
                <form method="post" action="/controllers/SignalementActionController.php" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                    <button type="submit" class="btn-red">Supprimer le signalement</button>
                </form>
            </div>
        <?php elseif ($isEtudiant): ?>
            <h2>Signaler cette annonce</h2>
            <form method="post" action="/controllers/SignalementController.php" style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; align-items: flex-end; gap: 1rem;">
                    <div style="flex: 1;">
                        <label for="type">Type de signalement</label>
                        <select name="type" id="type" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="Information fausse">Information fausse</option>
                            <option value="Harcèlement">Harcèlement</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <?php $idAnnonce = $_GET['id_annonce'] ?? ''; ?>
                    <?php if ($idAnnonce): ?>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="visibility: hidden;">IdAnnonce</label>
                        <a href="/views/ad.php?id=<?= urlencode($idAnnonce) ?>" style="display: inline-block; padding: 0.5rem 1rem; background: #3182ce; color: #fff; border-radius: 5px; text-decoration: none; font-size: 0.95rem;">IdAnnonce: <?= htmlspecialchars($idAnnonce) ?></a>
                    </div>
                    <?php endif; ?>
                </div>
                <label for="message">Message</label>
                <textarea name="message" id="message" rows="5" required placeholder="Décrivez le problème..."></textarea>
                <input type="hidden" name="id_annonce" value="<?= htmlspecialchars($idAnnonce) ?>">
                <button type="submit">Soumettre le signalement</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html> 