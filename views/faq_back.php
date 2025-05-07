<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="faq_back.css">
    
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
                        <img src="logo-removebg-preview.png" alt="logo">
                    </div>
                </div>
    
                <div class="nav-links">
                    <a href="#" class="nav-link">Offres</a>
                    <a href="#" class="nav-link">Messagerie</a>
                    <a href="/Home4Student-mvc/views/faq_back.html" class="nav-link active">FAQ</a>
                    <a href="/Home4Student-mvc/views/contact.html" class="nav-link">Contact</a>
    
                    <?php if (!isset($_SESSION['user'])): ?>
                        <a href="/Home4Student-mvc/views/login.html" class="btn btn-outline">Se connecter</a>
                        <a href="/Home4Student-mvc/views/register.html" class="btn btn-primary">S'inscrire</a>
                    <?php endif; ?>
                </div>
    
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="user-section">
                        <div id="userProfile" class="user-profile" onclick="window.location.href='/Home4Student-mvc/views/profile-edit.php'">
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
        <h2>Foire aux questions</h2>

        <?php
        $pdo = new PDO('mysql:host=localhost;dbname=home4student', 'root', '');
        $faqList = $pdo->query("SELECT * FROM faq WHERE reponse IS NOT NULL");

        while ($faq = $faqList->fetch(PDO::FETCH_ASSOC)):
        ?>
            <div class="faq-item">
                <button onclick="toggleFAQ(this)">
                    <?= htmlspecialchars($faq['question'], ENT_QUOTES, 'UTF-8') ?>
                </button>
                <div class="faq-content">
                    <?= nl2br(htmlspecialchars($faq['reponse'], ENT_QUOTES, 'UTF-8')) ?>
                </div>
            </div>
        <?php endwhile; ?>
    </section>



    <!-- Section poser une question -->
    <form id="questionForm">
        <label for="question">Posez votre question :</label>
        <textarea name="question" id="question" required></textarea>
        <button type="submit">Envoyer</button>
    </form>

    <script>
        document.getElementById("questionForm").addEventListener("submit", function(e) {
            e.preventDefault(); // Empêche le rechargement de la page

            const formData = new FormData(this);

            fetch("faq_poser_question.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert("Réponse serveur : " + data);
                document.getElementById("questionForm").reset();
            })
            .catch(error => {
                alert("Erreur lors de l'envoi.");
                console.error(error);
            });
        });
    </script>


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
        <div class="logo text-white mb-4">
            <i class="fas fa-graduation-cap text-2xl mr-2"></i>
            <span class="font-bold text-lg">Home4Student</span>
        </div>         
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
