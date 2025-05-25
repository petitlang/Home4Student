<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inscription - Home4Student</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" href="/views/register.css" />
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>
    <main>
      <div class="form-container">
        <h2>Créer un compte</h2>
        <form action="../controllers/UserController.php?action=register" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
          <div class="input-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" required placeholder="Votre nom">
          </div>
  
          <div class="input-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" required placeholder="Votre prénom">
          </div>
  
          <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="Votre email">
          </div>
  
          <div class="input-group">
            <label for="role">Je suis :</label>
            <select id="role" name="role" required>
              <option value="etudiant">Étudiant</option>
              <option value="proprietaire">Propriétaire</option>
              <!--<option value="admin">Administrateur</option>-->
            </select>
          </div>
  
          <div class="input-group password">
            <label for="password">Mot de passe</label>
            <input type="password" id="mdp" name="mdp" required placeholder="Mot de passe">
            <span id="togglePassword" class="password-toggle">👁️</span>
          </div>
  
          <div class="input-group password">
              <label for="confirm_mdp">Confirmez le mot de passe</label>
              <input type="password" name="confirm_mdp" id="confirm_mdp" placeholder="Confirmez votre mot de passe" required>
              <span id="confirm_togglePassword" class="confirm_password-toggle">👁️</span>
          </div>
          <p id="password-error-msg" style="color: red; font-size: 0.9rem;"></p>

          <div class="input-group"><label for="tele">Téléphone</label><input type="text" name="tele" placeholder="Entrez votre numéro de téléphone" required></div>
          <div class="input-group"><label for="genre">Genre</label><input type="text" name="genre" placeholder="Homme / Femme / Autre" required></div>
          <div class="input-group"><label for="rue">Rue</label><input type="text" name="rue" placeholder="Adresse de la rue" required></div>
          <div class="input-group"><label for="codepostal">Code postal</label><input type="text" name="codepostal" placeholder="75000" required></div>
          <div class="input-group"><label for="ville">Ville</label><input type="text" name="ville" placeholder="Paris, Lyon, etc." required></div>
          <div class="input-group"><label for="pays">Pays</label><input type="text" name="pays" placeholder="France, Belgique, ..." required></div>
          <div class="input-group"><label for="photo">Photo</label><input type="file" id="photo" name="photo" accept="image/*"></div>
          <div class="input-group"><label for="code">Code vérification</label><input type="text" name="code" placeholder="Entrez le code vérification" required></div>
          <button type="button" onclick="sendVerificationCode()" style="margin-top: 10px;">Envoyer le code</button>
          <p id="code-msg" style="color: green; font-size: 0.9rem;"></p>
          <p id="error-msg" style="color: red; text-align: center;"></p>
                
          <div class="cgu-group">
              <input type="checkbox" id="cgu" name="cgu" required>
              <label for="cgu">J'accepte les <a href="/views/CGU.php">CGU</a> et les conditions d'utilisation du site.</label>
          </div>
  
          <button type="submit" class="btn">S'inscrire</button>
        </form>
  
        <p>Déjà un compte ? <a href="/Home4Student-mvc/views/login.php">Se connecter</a></p>
      </div>
    </main>

<?php include __DIR__ . '/footer.html'; ?>

    <script>
      const params = new URLSearchParams(window.location.search);
      if (params.get("error") === "verification") {
          document.getElementById("error-msg").textContent = "Code de vérification incorrect ou expiré.";
          alert("Code de vérification incorrect ou expiré.");
      }
      
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
                  msg.style.color = "green";
                  msg.textContent = text;
              })
              .catch(error => {
                  msg.style.color = "red";
                  msg.textContent = "Erreur lors de l'envoi du code.";
              });
          }
      
        function validateForm() {
            const mdp = document.getElementById("mdp").value;
            const confirmMdp = document.getElementById("confirm_mdp").value;
            const errorMsg = document.getElementById("password-error-msg");
        
            // Expressions régulières : au moins 8 bits avec majuscules et minuscules, caractères spéciaux
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}$/;
        
            if (!regex.test(mdp)) {
                errorMsg.textContent = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule et un symbole.";
                return false;
            }
        
            if (mdp !== confirmMdp) {
                errorMsg.textContent = "Les mots de passe ne correspondent pas.";
                return false;
            }
        
            errorMsg.textContent = "";
            return true;
        }
      
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('mdp');
      
        togglePassword.addEventListener('click', function () {
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);
          this.textContent = type === 'password' ? '👁️' : '🙈';
        });
      
        const confirm_togglePassword = document.getElementById('confirm_togglePassword');
        const confirm_password = document.getElementById('confirm_mdp');
      
        confirm_togglePassword.addEventListener('click', function () {
          const confirm_type = confirm_password.getAttribute('type') === 'password' ? 'text' : 'password';
          confirm_password.setAttribute('type', confirm_type);
          this.textContent = confirm_type === 'password' ? '👁️' : '🙈';
        });
      </script> 

</body>
</html>


