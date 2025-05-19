<?php
session_start();

// Simuler un utilisateur admin pour test
$_SESSION['user'] = [
    'nom' => 'Zabala',
    'prenom' => 'Danaé',
    'role' => 'admin'
];

// Connexion DB
$pdo = new PDO('mysql:host=localhost;dbname=home4student', 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Admin</title>
    <link rel="stylesheet" href="/views/faq_back.css">

    <script defer>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.getElementById("adminAddFaqForm");
            const message = document.getElementById("adminMessage");
            const faqContainer = document.getElementById("publicFaq");

            if (form) {
                form.addEventListener("submit", function (e) {
                    e.preventDefault();
                    const formData = new FormData(form);

                    fetch("faq_ajouter_question.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            message.textContent = data.message;

                            const newFaq = document.createElement("div");
                            newFaq.className = "faq-item";
                            newFaq.innerHTML = `
                                <p><strong>Question :</strong> ${data.question}</p>
                                <p><strong>Réponse :</strong> ${data.reponse}</p>
                                <hr>`;
                            faqContainer.prepend(newFaq);
                            form.reset();
                        } else {
                            message.textContent = data.message;
                        }
                    })
                    .catch(() => {
                        message.textContent = "Erreur serveur.";
                    });
                });
            }
        });
    </script>
</head>
<body>

<header>
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <img src="/views/logo-removebg-preview.png" alt="logo">
            </div>
            <div class="nav-links">
                <a href="#" class="nav-link">Offres</a>
                <a href="#" class="nav-link">Messagerie</a>
                <a href="/views/faq_back.php" class="nav-link active">FAQ</a>
                <a href="/views/contact.html" class="nav-link">Contact</a>
                <?php if (!isset($_SESSION['user'])): ?>
                    <a href="/views/login.html" class="btn btn-outline">Se connecter</a>
                    <a href="/views/register.html" class="btn btn-primary">S'inscrire</a>
                <?php else: ?>
                    <a href="/controllers/UserController.php?action=logout" class="btn btn-secondary">Se déconnecter</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>

<main>
    <section class="faq">
        <h1>FAQ</h1>
        <p>Retrouvez ci-dessous toutes les informations utiles</p>
    </section>

        <!-- FAQ publique -->
        <section id="publicFaq" class="public-faq">
            <h2>Foire aux questions</h2>
            <?php
            $faqs = $pdo->query("SELECT * FROM faq WHERE reponse IS NOT NULL ORDER BY IdFAQ DESC");
            if ($faqs->rowCount() > 0):
                while ($faq = $faqs->fetch(PDO::FETCH_ASSOC)):
            ?>
                <div class="faq-item" id="faq-<?= $faq['IdFAQ'] ?>">
                    <?php if (isset($_GET['edit']) && $_GET['edit'] == $faq['IdFAQ'] && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <!-- Formulaire édition -->
                        <form method="POST" action="faq_modifier_question.php">
                            <input type="hidden" name="id" value="<?= $faq['IdFAQ'] ?>">
                            <label>Question :</label><br>
                            <textarea name="question" required><?= htmlspecialchars($faq['question']) ?></textarea><br>
                            
                            <label>Réponse :</label><br>
                            <textarea name="reponse" required><?= htmlspecialchars($faq['reponse']) ?></textarea><br>
                            
                            <button type="submit">Enregistrer</button>
                            <a href="faq_back.php">Annuler</a>
                        </form>
                    <?php else: ?>
                        <p><strong>Question :</strong> <?= htmlspecialchars($faq['question']) ?></p>
                        <p><strong>Réponse :</strong> <?= nl2br(htmlspecialchars($faq['reponse'])) ?></p>

                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <a href="faq_back.php?edit=<?= $faq['IdFAQ'] ?>" class="btn-modifier">✏️ Modifier</a>

                            <!-- Formulaire suppression en POST pour sécurité -->
                            <form method="POST" action="faq_supprimer_question.php" style="display:inline;" onsubmit="return confirm('Voulez-vous vraiment supprimer cette question ?');">
                                <input type="hidden" name="id" value="<?= $faq['IdFAQ'] ?>">
                                <button type="submit" class="btn-supprimer" style="background:none; border:none; color:red; cursor:pointer;">🗑️ Supprimer</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <hr>           
                
            
            <?php endwhile; else: ?>
            <p>Aucune question n’a encore été répondue.</p>
        <?php endif; ?>
    </section>

    <!-- Formulaire d'ajout admin -->
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <section class="admin-add-faq">
            <h2>Ajouter une nouvelle question/réponse</h2>
            <form id="adminAddFaqForm">
                <label for="question_admin">Question :</label>
                <textarea name="question" id="question_admin" required></textarea>

                <label for="reponse_admin">Réponse :</label>
                <textarea name="reponse" id="reponse_admin" required></textarea>

                <button type="submit">Ajouter à la FAQ</button>
            </form>
            <p id="adminMessage" class="info-message"></p>
        </section>
    <?php endif; ?>    

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
