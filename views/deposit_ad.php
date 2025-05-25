<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Déposer une annonce</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="/views/deposit_ad.css">
</head>

<body>
    <?php include __DIR__ . '/header.php'; ?>

    <div class="hero-slogan">
        <h1>Bienvenue, <?php echo htmlspecialchars($user["prenom"]); ?> !</h1>
    </div>

    <div class="main-grid">
        <div class="card">
            <h2>Des logements fiables, vérifiés par nos équipes</h2>
            <p>Une communication directe avec le propriétaire</p>
            <div style="margin: 1.5rem 0;">
                <p>✓ Logement vérifié</p>
                <p>✓ Certification qualité</p>
                <p>✓ Inspection mensuelle</p>
                <p>✓ Garantie de sécurité</p>
            </div>
            <a href="/views/ads_list.html" class="btn btn-primary">Voir les annonces</a>
        </div>

        <div class="card">
            <h2>Vendez votre bien immobilier</h2>
            <p>Présentez votre bien en quelques clics</p>
            <div style="margin: 1.5rem 0;">
                <p>✔ Définissez votre prix</p>
                <p>✔ Mettez en avant les atouts</p>
                <p>✔ Publication instantanée</p>
            </div>
            <a href="/views/deposit_ad.php" class="btn btn-secondary">Déposer une annonce</a>
        </div>
    </div>

    <?php include __DIR__ . '/footer.html'; ?>
</body>
</html>