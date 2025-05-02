<?php
require_once __DIR__ . '/../models/init_database.php';
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index2.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se loger Facilement</title>
   <link rel="stylesheet" href="index.css">
</head>
<body>
    <header>
        <div class="logo">SeLogerFacilement</div>
        <nav>
         <a href="/Home4Student-mvc/views/contact.html">Contact</a>
            <a href="#messagerie">Messagerie</a>
            <a href="#faq">FAQ</a>
            <a href="#offres">Offres</a>
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="/Home4Student-mvc/views/login.html" class="btn btn-secondary">Se connecter</a>
                <a href="/Home4Student-mvc/views/register.html" class="btn btn-primary">S'inscrire</a>
            <?php else: ?>
                <a href="/Home4Student-mvc/views/index2.php" class="btn btn-primary">Vers mon espace</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="hero-slogan">
        <h1>Pour les étudiants, par des étudiants</h1>
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
            <a href="/Home4Student-mvc/views/ads_list.html" class="btn btn-primary">Voir les annonces</a>
        </div>

        <div class="card">
            <h2>Vendez votre bien immobilier</h2>
            <p>Présentez votre bien en quelques clics</p>
            <div style="margin: 1.5rem 0;">
                <p>✔ Définissez votre prix</p>
                <p>✔ Mettez en avant les atouts</p>
                <p>✔ Publication instantanée</p>
            </div>
            <a href="/Home4Student-mvc/views/login.html" class="btn btn-secondary">Déposer une annonce</a>
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
                <a href="/Home4Student-mvc/views/contact.html">Contact</a>
                <a href="#faq">FAQ</a>
                <a href="#guides">Guides</a>
            </div>
            <div class="footer-section">
                <h4>Légal</h4>
                <a href="/Home4Student-mvc/views/cgu.html">CGU</a> 
                <a href="#privacy">Confidentialité</a>
                <a href="#cookies">Cookies</a>
            </div>
        </div>
    </footer>

    
</body>
</html>