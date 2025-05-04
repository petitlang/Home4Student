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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        :root {
            --primary: #2ecc71;
            --secondary: #e74c3c;
            --accent: #3498db;
            --dark: #2c3e50;
            --light: #ecf0f1;
        }

        body {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                        url('https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            min-height: 100vh;
        }

        header {
            padding: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(44, 62, 80, 0.95);
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary);
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-left: 2rem;
            transition: color 0.3s;
        }

        nav a:hover {
            color: var(--primary);
        }

        .hero-slogan {
            text-align: center;
            padding: 4rem 2rem;
            background: rgba(44, 62, 80, 0.9);
            margin-bottom: 4rem;
        }

        .hero-slogan h1 {
            font-size: 2.5rem;
            color: var(--primary);
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .main-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            color: var(--dark);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h2 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        .card p {
            line-height: 1.6;
            margin: 1rem 0;
            color: #666;
        }

        .btn {
            display: inline-block;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 1.5rem;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #27ae60;
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
        }

        .btn-secondary:hover {
            background: #c0392b;
        }

        footer {
            background: rgba(44, 62, 80, 0.95);
            padding: 3rem;
            margin-top: auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-section h4 {
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .footer-section a {
            color: var(--light);
            text-decoration: none;
            display: block;
            margin: 0.5rem 0;
        }

        .footer-section a:hover {
            color: white;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">SeLogerFacilement</div>
        <nav>
            <a href="#contact">Contact</a>
            <a href="#messagerie">Messagerie</a>
            <a href="#faq">FAQ</a>
            <a href="#offres">Offres</a>
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="/views/login.html" class="btn btn-secondary">Se connecter</a>
                <a href="/views/register.html" class="btn btn-primary">S'inscrire</a>
            <?php else: ?>
                <a href="/views/index2.php" class="btn btn-primary">Vers mon espace</a>
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
            <a href="/views/search.html" class="btn btn-primary">Voir les annonces</a>
        </div>

        <div class="card">
            <h2>Vendez votre bien immobilier</h2>
            <p>Présentez votre bien en quelques clics</p>
            <div style="margin: 1.5rem 0;">
                <p>✔ Définissez votre prix</p>
                <p>✔ Mettez en avant les atouts</p>
                <p>✔ Publication instantanée</p>
            </div>
            <a href="/views/login.html" class="btn btn-secondary">Déposer une annonce</a>
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
                <a href="/views/cgu.html">CGU</a> 
                <a href="#privacy">Confidentialité</a>
                <a href="#cookies">Cookies</a>
            </div>
        </div>
    </footer>

    
</body>
</html>