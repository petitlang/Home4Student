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
                        <a href="/Home4Student-mvc/views/login.html" class="btn btn-outline">Sign in</a>
                        <a href="/Home4Student-mvc/views/register.html" class="btn btn-primary">Register</a>
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
        <?php
    $pdo = new PDO('mysql:host=localhost;dbname=home4student', 'root', '');
    $faqList = $pdo->query("SELECT * FROM faq WHERE reponse IS NOT NULL ORDER BY created_at DESC");

    while ($faq = $faqList->fetch(PDO::FETCH_ASSOC)):
    ?>
        <div class="faq-item">
            <button onclick="toggleFAQ(this)"><?= htmlspecialchars($faq['question']) ?></button>
            <div class="faq-content"><?= htmlspecialchars($faq['reponse']) ?></div>
        </div>
    <?php endwhile; ?>


    <!-- Section poser une question -->
    <section class="ask-question">
        <h2>Vous avez une question ?</h2>
        <form action="poser_question.php" method="POST">
            <label for="question">Posez votre question :</label>
            <textarea name="question" id="question" required></textarea>
            <button type="submit">Envoyer</button>
        </form>
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

    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
    <section class="admin-answers">
        <h2>Questions en attente</h2>
        <?php
        $pdo = new PDO('mysql:host=localhost;dbname=home4student', 'root', '');
        $stmt = $pdo->query("SELECT * FROM faq WHERE reponse IS NULL");

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
        ?>
            <form action="repondre_question.php" method="POST">
                <p><strong>Question :</strong> <?= htmlspecialchars($row['question']) ?></p>
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                <textarea name="reponse" placeholder="Votre réponse ici..." required></textarea>
                <button type="submit">Répondre</button>
            </form>
            <hr>
        <?php endwhile; ?>
    </section>
    <?php endif; ?>

    

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
