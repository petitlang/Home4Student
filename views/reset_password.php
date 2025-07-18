<?php
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
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="reset_password.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <div class="login-section">
        <div class="login-container">
            <h2>Réinitialiser le mot de passe</h2>
            <form action="../controllers/UserController.php?action=reset_password" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required placeholder="Entrez votre email">
                </div>
                <div class="input-group">
                    <label for="code">Code vérification</label>
                    <input type="text" id="code" name="code" placeholder="Entrez le code vérification" required>
                </div>
                <div class="input-group">
                    <label for="password">Nouveau mot de passe</label>
                    <input type="password" id="password" name="new_password" required placeholder="Entrez le nouveau mot de passe">
                </div>
                <div class="input-group">
                    <label for="role">Je suis :</label>
                    <select name="role" required>
                        <option value="etudiant">Étudiant</option>
                        <option value="proprietaire">Propriétaire</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </div>
                <button type="button" onclick="sendVerificationCode()" class="btn" style="margin-top: 10px;">Envoyer le code</button>
                <p id="code-msg" style="color: green; font-size: 0.9rem;"></p>
                <p id="error-msg" style="color: red; text-align: center;"></p>
                <button type="submit" class="btn btn-primary">Réinitialiser le mot de passe</button>
            </form>
            <p class="noir-text">Retour à la connexion ? <a href="/views/login.php">Se connecter</a></p>
        </div>
    </div>

    <?php include __DIR__ . '/footer.html'; ?>
</body>
</html>

<script>
    const params = new URLSearchParams(window.location.search);
    if (params.get("error") === "verification") {
        document.getElementById("error-msg").textContent = "Code de vérification incorrect.";
        alert("Code de vérification incorrect.");                
    }else if (params.get("error") === "email") {
        document.getElementById("error-msg").textContent = "Email non trouvé.";
        alert("Email non trouvé.");
    }else if (params.get("error") === "timeout") {
        document.getElementById("error-msg").textContent = "Le code de vérification a expiré.";
        alert("Le code de vérification a expiré.");
    }
    </script>
    
    
    <script>
        function sendVerificationCode() {
            const email = document.querySelector('input[name="email"]').value;
            const msg = document.getElementById("code-msg");
        
            if (!email) {
                msg.style.color = "red";
                msg.textContent = "Veuillez entrer votre email avant d'envoyer le code.";
                return;
            }
        
            fetch("../controllers/verification_code.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `email=${encodeURIComponent(email)}`
            })
            .then(response => response.text())
            .then(text => {
                console.log("Réponse brute du serveur:", text);
                msg.style.color = "green";
                msg.textContent = text;
            })
            .catch(error => {
                msg.style.color = "red";
                msg.textContent = "Erreur lors de l'envoi du code.";
            });
        }
        </script>