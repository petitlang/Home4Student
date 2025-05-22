<?php
session_start();




$_SESSION['user'] = [
    'nom' => 'Zabala',
    'prenom' => 'Danaé',
    'role' => 'admin'
];
$user = $_SESSION['user'] ?? null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>FAQ - Admin</title>
   
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/views/header_footer.css" />
</head>

<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <img src="/views/logo-removebg-preview.png" alt="logo" />
            </div>

            <div class="nav-links">
                <a href="/views/ads_lists.php" class="btn btn-outline nav-link">Offres</a>
                <a href="/views/chat.php" class="btn btn-outline nav-link">Messagerie</a>
                <a href="/views/favoris.php" class="btn btn-outline nav-link">Favoris</a>
                <a href="/views/faq_back.php" class="btn btn-primary nav-link">FAQ</a>
                <a href="/views/contact.html" class="btn btn-outline nav-link">Contact</a>
                <a href="/views/index2.php" class="btn btn-outline nav-link"><P>Page d'accueil</P></a>

                
                    <?php if (!isset($_SESSION['user'])): ?>
                        <div class="user">
                            <a href="/views/login.html" class="btn btn-outline">Se connecter</a>
                            <a href="/views/register.html" class="btn btn-secondary">S'inscrire</a>
                        </div>
                    <?php else: ?>

                        <div class="user-section">
                            <div id="userProfile" class="user-profile" onclick="window.location.href='/views/mon_profile.php'">
                                <?= htmlspecialchars($user["prenom"] . " " . $user["nom"]) ?>
                                <div id="userAvatar">
                                    <?php if (!empty($user["photo"])): ?>
                                        <img src="<?= htmlspecialchars($user["photo"]) ?>" alt="Photo de profil" class="profile-img" />
                                    <?php else: ?>
                                        <div class="default-avatar"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <a href="../controllers/UserController.php?action=logout" class="btn btn-secondary">Se déconnecter</a>
                        </div>
                    <?php endif; ?>
                    
            </div>
        </nav>
    </header>

    <main class="main-content">
        <h1></h1>
        <p>Voici le contenu de la page...</p>
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="logo">
                    <img src="/views/logo-removebg-preview.png" alt="logo" />
                </div>

                
                    <div class="social-links">
                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
                    </div>
                
                
                        <h3 class="footer-heading">L'entreprise</h3>
                        <ul class="footer-links">
                            <li class="footer-link"><a href="#">Qui sommes-nous ?</a></li>
                            <li class="footer-link"><a href="/views/contact.html">Nous contacter</a></li>
                        </ul>
                

                
                        <h3 class="footer-heading">Services pro</h3>
                        <ul class="footer-links">
                            <li class="footer-link"><a href="#">Accès client</a></li>
                        </ul>
                

                <div>
                        <h3 class="footer-heading">À découvrir</h3>
                        <ul class="footer-links">
                            <li class="footer-link"><a href="#">Tout l'immobilier</a></li>
                            <li class="footer-link"><a href="#">Toutes les villes</a></li>
                            <li class="footer-link"><a href="#">Tous les départements</a></li>
                            <li class="footer-link"><a href="#">Toutes les régions</a></li>
                        </ul>
                </div>    
            </div>        
        </div>       
        
    </footer>
</body>
</html>
