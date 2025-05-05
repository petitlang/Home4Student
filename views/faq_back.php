<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/views/faq_back.css">
    
    <script defer>
        function toggleFAQ(element) {
            element.nextElementSibling.classList.toggle("active");
        }
    </script>
</head>
<body>
    
     

    <!-- Barre de navigation -->
    <header>
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <div class="flex items-center">
                        <img src="/views/logo-removebg-preview.png" alt="logo">
                    </div>
                </div>
    
                <div class="nav-links">
                    <a href="#" class="nav-link">Offres</a>
                    <a href="#" class="nav-link">Messagerie</a>
                    <a href="/views/faq_back.html" class="nav-link active">FAQ</a>
                    <a href="/views/contact.html" class="nav-link">Contact</a>
    
                    <?php if (!isset($_SESSION['user'])): ?>
                        <a href="/views/login.html" class="btn btn-outline">Sign in</a>
                        <a href="/views/register.html" class="btn btn-primary">Register</a>
                    <?php endif; ?>
                </div>
    
                <?php if (isset($_SESSION['user'])): ?>
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
                <?php endif; ?>
            </nav>
        </div>
    </header>
    

    <!---Section FAQ texte-->
    <section class="faq">
        <h1>FAQ</h1>
        <p>Retrouvez ci-dessous toutes les informations utiles</p>
    </section>

    <!-- Section FAQ -->
    <section class="faq-container">        
        <div class="background-photo"></div>
        <div class="faq-list">
            <div class="faq-item">
                <button onclick="toggleFAQ(this)">Question 1</button>
                <div class="faq-content">Réponse à la question 1.</div>
            </div>
            <div class="faq-item">
                <button onclick="toggleFAQ(this)">Question 2</button>
                <div class="faq-content">Réponse à la question 2.</div>
            </div>
            <div class="faq-item">
                <button onclick="toggleFAQ(this)">Question 3</button>
                <div class="faq-content">Réponse à la question 3.</div>
            </div>
            <div class="faq-item">
                <button onclick="toggleFAQ(this)">Question 4</button>
                <div class="faq-content">Réponse à la question 4.</div>
            </div>
            <div class="faq-item">
                <button onclick="toggleFAQ(this)">Question 5</button>
                <div class="faq-content">Réponse à la question 5.</div>
            </div>
        </div>
    </section>

    <!-- Formulaire de contact -->
    <section class="contact">
        <h2>Nous contacter</h2>
        <form>
            <div class="form-left">
                <label>Nom *</label>
                <input type="text" required>
                <label>Adresse mail *</label>
                <input type="email" required>
            </div>
            <div class="form-right">
                <label>Message...</label>
                <textarea required></textarea>
            </div>
            <button class="envoyer" type="submit">Envoyer</button>
        </form>
    </section>
    

    <!-- Pied de page -->
    <footer>     
        <div class="img"><img src="C:/Users/danz/Desktop/site_vrai/images/logo.PNG"> </div>          
        <div class="links">
            <div>
                <h4>L'entreprise</h4>
                <a href="#">Qui sommes-nous ?</a>
                <a href="#">Nous contacter</a>
            </div>
            <div>
                <h4>Service pro</h4>
                <a href="#">Tous nos services</a>
                <a href="#">Accès client</a>
            </div>
            <div>
                <h4>A découvrir</h4>
                <a href="#">Tout l'immobilier</a>
                <a href="#">Toutes les villes</a>
            </div>
        </div>
    </footer>

</body>
</html>
