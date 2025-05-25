<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/views/login.css">
</head>
<body>

<?php include __DIR__ . '/header.php'; ?>

  <div class="login-section">
    <div class="login-container">
      <h2>Se connecter</h2>
      <form action="../controllers/UserController.php?action=login" method="POST">
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required placeholder="Entrez votre email">
        </div>

        <div class="input-group">
          <label for="role">Je suis :</label>
          <select name="role" required>
            <option value="etudiant">Étudiant</option>
            <option value="proprietaire">Propriétaire</option>
            <option value="admin">Administrateur</option>
          </select>
        </div>

        <div class="input-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" required placeholder="Entrez votre mot de passe">
          <span id="togglePassword" class="password-toggle">👁️</span>
        </div>

        <button type="submit" class="btn-primary">Se connecter</button>
      </form>

      <p>Pas encore de compte ? <a href="/views/register.php">S'inscrire</a></p>
      <p><a href="/views/reset_password.php" class="forgot-password">Mot de passe oublié ?</a></p>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.html'; ?>

  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      const passwordInput = document.getElementById('password');
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      this.textContent = type === 'password' ? '👁️' : '🙈';
    });
  </script>
</body>
</html>

