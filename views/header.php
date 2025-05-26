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
                <a href="/views/search.php" class="nav-link">Offres</a>
                <?php
                if (isset($_SESSION['user'])) {
                    // 已登录，跳转到 chat.php
                    echo '<a href="/controllers/ChatController.php?action=getNav" class="nav-link">Messagerie</a>';
                } else {
                    // 未登录，跳转到登录页
                    echo '<a href="/views/login.php" class="nav-link">Messagerie</a>';
                }
                ?>
                <a href="/views/faq_back.php" class="nav-link">FAQ</a>
                <a href="/views/contact.php" class="nav-link">Contact</a>
                <a href="/views/Forum.html" class="nav-link">Forum</a>
            </div>

            <div class="user-section">
                <?php
                
                if (!isset($_SESSION['user'])) {
                    // 未登录，显示登录和注册按钮
                    echo '<div class="perso">
                            <a href="/views/login.php" class="btn btn-outline">Se connecter</a>
                            <a href="/views/register.php" class="btn btn-primary">S\'inscrire</a>
                        </div>';
                } else {
                    // 已登录，显示用户信息和退出按钮
                    echo '<div id="userProfile" class="user-profile" onclick="window.location.href=\'/views/mon_profile.php\'">';
                    echo htmlspecialchars($_SESSION['user']["prenom"] . " " . $_SESSION['user']["nom"]);
                    echo '<div id="userAvatar">';
                    if (!empty($_SESSION['user']['photo'])) {
                        echo '<img src="' . htmlspecialchars($_SESSION['user']["photo"]) . '" alt="Photo de profil" class="profile-img">';
                    } else {
                        echo '<div class="default-avatar"></div>';
                    }
                    echo '</div></div>';
                    echo '<a href="../controllers/UserController.php?action=logout" class="btn btn-secondary">Se déconnecter</a>';
                }
                $currentFile = basename($_SERVER['SCRIPT_NAME']);
                if ($currentFile !== 'index.php' && $currentFile !== 'index2.php') {
                    echo '<a href="/views/index2.php" class="btn btn-home btn-secondary">Page d\'accueil</a>';
                }
                ?>
            </div>
        </nav>
    </div>
</header> 
</body>
</html>