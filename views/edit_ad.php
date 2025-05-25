<?php
require_once __DIR__ . '/../models/Admodel.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    exit('Accès refusé');
}
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) exit('Paramètre id manquant');
$ad = ad_get($id);
if (!$ad) exit('Annonce introuvable');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'Titre'        => $_POST['Titre'],
        'Type'         => $_POST['Type'],
        'Prix'         => $_POST['Prix'],
        'Etat'         => $_POST['Etat'],
        'rue'          => $_POST['rue'],
        'codepostal'   => $_POST['codepostal'],
        'ville'        => $_POST['ville'],
        'Pays'         => $_POST['Pays'],
        'Descriptions' => $_POST['Descriptions'],
    ];
    if (ad_update($id, $data)) {
        header("Location: /views/ad.php?id=$id");
        exit;
    } else {
        $error = "Erreur lors de la mise à jour.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'annonce</title>
    <style>
        form { max-width: 600px; margin: 2rem auto; display: flex; flex-direction: column; gap: 1rem; }
        label { font-weight: bold; }
        input, textarea { width: 100%; padding: 0.5rem; }
        button { background: #38a169; color: #fff; border: none; padding: 0.7rem 1.5rem; border-radius: 6px; cursor: pointer; }
        button:hover { background: #276749; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <h2>Modifier l'annonce</h2>
    <?php if (!empty($error)) echo '<div style="color:red;">'.$error.'</div>'; ?>
    <form method="post">
        <label>Titre <input type="text" name="Titre" value="<?= htmlspecialchars($ad['Titre']) ?>" required></label>
        <label>Type <input type="text" name="Type" value="<?= htmlspecialchars($ad['Type']) ?>" required></label>
        <label>Prix <input type="number" name="Prix" value="<?= htmlspecialchars($ad['Prix']) ?>" required></label>
        <label>Etat <input type="text" name="Etat" value="<?= htmlspecialchars($ad['Etat']) ?>" required></label>
        <label>Rue <input type="text" name="rue" value="<?= htmlspecialchars($ad['rue']) ?>" required></label>
        <label>Code postal <input type="text" name="codepostal" value="<?= htmlspecialchars($ad['codepostal']) ?>" required></label>
        <label>Ville <input type="text" name="ville" value="<?= htmlspecialchars($ad['ville']) ?>" required></label>
        <label>Pays <input type="text" name="Pays" value="<?= htmlspecialchars($ad['Pays']) ?>" required></label>
        <label>Descriptions <textarea name="Descriptions" required><?= htmlspecialchars($ad['Descriptions']) ?></textarea></label>
        <button type="submit">Enregistrer</button>
    </form>
    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html> 