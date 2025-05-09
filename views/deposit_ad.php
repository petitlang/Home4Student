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
    <header>
        <div class="logo">SeLogerFacilement</div>
        <nav>
            <a href="/views/index2.php" class="btn btn-primary">Retour à l'accueil</a>
            <div class="nav-links">
                <a href="#contact">Contact</a>
                <a href="#messagerie">Messagerie</a>
                <a href="#faq">FAQ</a>
                <a href="#offres">Offres</a>
            </div>
            <div class="user-section">
                <div id="userProfile" class="user-profile" onclick="window.location.href='/views/profile-edit.php'">
                    <?php echo htmlspecialchars($user["prenom"] . " " . $user["nom"]); ?>
                    <div id="userAvatar">
                        <?php if (!empty($user["photo"])): ?>
                            <img src="<?php echo htmlspecialchars($user["photo"]); ?>" alt="Photo de profil" class="profile-img">
                        <?php else: ?>
                            <div class="default-avatar"></div>
                        <?php endif; ?>
                    </div>
                </div>
                <a href="../controllers/UserController.php?action=logout" class="btn btn-secondary">Se déconnecter</a>
            </div>
        </nav>
    </header>

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

    <footer>
        <div class="footer-grid">
            <div class="footer-section">
                <h4>Notre entreprise</h4>
                <a href="#about">Qui sommes-nous ?</a>
                <a href="#team">Notre équipe</a>
                <a href="#press">Presse</a>
            </div>
            <div class="footer-section">
                <h4>Services pro</h4>
                <a href="#partners">Partenariats</a>
                <a href="#agencies">Agences</a>
                <a href="#pro-tools">Outils professionnels</a>
            </div>
            <div class="footer-section">
                <h4>Aide</h4>
                <a href="#contact">Contact</a>
                <a href="#faq">FAQ</a>
                <a href="#guides">Guides</a>
            </div>
            <div class="footer-section">
                <h4>Légal</h4>
                <a href="/views/cgu.html">CGU</a> <!-- Lien corrigé vers cgu.html -->
                <a href="#privacy">Confidentialité</a>
                <a href="#cookies">Cookies</a>
            </div>
        </div>
    </footer>

    
</body>
</html>