<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="/views/header.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeStudent</title>
</head>

<body>
<header>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <div class="flex items-center">
                    <img src="/views/logo-removebg-preview.png" alt="logo"></img>
                </div>
            </div>
            <div class="nav-links">
                <a href="/views/ads_list.php" class="nav-link">Offres</a>
                <a href="/views/chat.php" class="nav-link">Messagerie</a>
                <a href="/views/faq_back.php" class="nav-link">FAQ</a>
                <a href="/views/contact.html" class="nav-link">Contact</a>
            </div>

            <div class="user-section">
                <?php
                
                if (!isset($_SESSION['user'])) {
                    // 未登录，显示登录和注册按钮
                    echo '<div class="perso">
                            <a href="/views/login.html" class="btn btn-outline">Se connecter</a>
                            <a href="/views/register.html" class="btn btn-primary">S\'inscrire</a>
                        </div>';
                } else {
                    // 已登录，显示用户信息和退出按钮
                    echo '<div id="userProfile" class="user-profile" onclick="window.location.href=\'/views/mon_profile.php\'">';
                    echo htmlspecialchars($user["prenom"] . " " . $user["nom"]);
                    echo '<div id="userAvatar">';
                    if (!empty($user["photo"])) {
                        echo '<img src="' . htmlspecialchars($user["photo"]) . '" alt="Photo de profil" class="profile-img">';
                    } else {
                        echo '<div class="default-avatar"></div>';
                    }
                    echo '</div></div>';
                    echo '<a href="../controllers/UserController.php?action=logout" class="btn btn-secondary">Se déconnecter</a>';
                }
                $currentFile = basename($_SERVER['SCRIPT_NAME']);
                if ($currentFile !== 'index.php' && $currentFile !== 'index2.php') {
                    echo '<a href="/views/index2.php" class="btn btn-solid" style="margin-right:1rem;">Page d\'accueil</a>';
                }
                ?>
            </div>
        </nav>
    </div>
</header> 
</body>
</html>