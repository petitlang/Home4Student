<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /views/login.html');
    exit();
}
$user = $_SESSION['user'];
$role = $user['role'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Mon Profil - SeLogerFacilement</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="/views/mon_profil.css">
</head>
<body>

<!-- Header (search page style) -->
<header>
  <nav class="navbar">
    <div class="logo">
      <img src="/views/logo-removebg-preview.png" alt="logo" />
    </div>
    <div class="nav-center">
      <div class="nav-links">
        <a href="/views/ads_list.php">Offres</a>
        <a href="/views/chat.php">Messagerie</a>
        <a href="/views/faq_back.html">FAQ</a>
        <a href="/views/contact.html">Contact</a>
        <a href="/views/cgu.html">CGU</a>
      </div>
    </div>
    <div class="nav-buttons">
      <a href="/views/index2.php" class="btn-solid">Page d'accueil</a>
    </div>
  </nav>
</header>

<div class="container">
  <!-- Profile Card Only -->
  <div class="card">
    <div class="avatar-container">
      <label class="avatar">
        <img id="avatarPreview" src="<?php echo htmlspecialchars($user['photo']); ?>" alt="Avatar utilisateur">
        <input type="file" id="avatarUpload" accept="image/*">
      </label>
      <div class="avatar-upload">Changer la photo</div>
    </div>
    <div class="name"><?php echo htmlspecialchars($user['prenom']) . ' ' . htmlspecialchars($user['nom']); ?></div>
    <div class="role">
      <?php echo ($role === 'etudiant') ? 'Étudiante' : (($role === 'proprietaire') ? 'Propriétaire' : 'Admin'); ?>
    </div>

    <div class="info">
      <div class="info-title">
        <i class="fas fa-user-shield"></i>
        Informations confirmées
      </div>
      <div class="info-item">
        <i class="fas fa-phone"></i>
        <span><?php echo htmlspecialchars($user['Tele'] ?? 'Non renseigné'); ?></span>
        <a href="/views/profile-edit.php" class="verification-badge verified" style="text-decoration:none;">Vérifié</a>
      </div>
      <div class="info-item">
        <i class="fas fa-envelope"></i>
        <span><?php echo htmlspecialchars($user['Email'] ?? 'Non renseigné'); ?></span>
        <a href="/views/profile-edit.php" class="verification-badge verified" style="text-decoration:none;">Vérifié</a>
      </div>
    </div>
    

    <!-- Unified Action Buttons -->
    <div class="action-buttons" style="margin-top: 2.5rem;">
      <?php if ($role === 'etudiant'): ?>
        <div class="info">
          <div class="info-title">
            <i class="fas fa-id-card"></i>
            Compléter mon profil
          </div>
        </div>
        <div class="profile-actions">
          <a href="/views/profile-edit.php"><i class="fas fa-user-check"></i> Compléter mon profil</a>
          <a href="/views/candidature.php"><i class="fas fa-file-alt"></i> Mes Candidatures</a>
          <?php
            $showChat = false;
            if (isset($_SESSION['user']) && isset($user['role'])) {
              $userRole = $_SESSION['user']['role'];
              $profileRole = $user['role'];
              if ($userRole !== $profileRole) {
                $showChat = true;
              }
            }
            if ($showChat):
          ?>
          <a href="/views/chat.php"><i class="fas fa-comments"></i> Chat</a>
          <?php endif; ?>
        </div>
      <?php elseif ($role === 'proprietaire'): ?>
        <div class="info">
          <div class="info-title">
            <i class="fas fa-id-card"></i>
            Compléter mon profil
          </div>
        </div>
        <div class="profile-actions">
          <a href="/views/profile-edit.php"><i class="fas fa-user-check"></i> Compléter mon profil</a>
          <a href="/views/mes_annonces.php"><i class="fas fa-home"></i> Mes Annonces</a>
          <?php
            $showChat = false;
            if (isset($_SESSION['user']) && isset($user['role'])) {
              $userRole = $_SESSION['user']['role'];
              $profileRole = $user['role'];
              if ($userRole !== $profileRole) {
                $showChat = true;
              }
            }
            if ($showChat):
          ?>
          <a href="/views/chat.php"><i class="fas fa-comments"></i> Chat</a>
          <?php endif; ?>
        </div>
      <?php elseif ($role === 'admin'): ?>
        <div class="profile-actions">
          <a href="/views/signalements_list.php" style="display:inline-block; padding:0.75rem 2rem; background:#6b9080; color:#fff; border-radius:6px; font-weight:600; text-decoration:none; margin-top:1rem;">
            <i class="fas fa-flag"></i> Voir les signalements
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/footer.html'; ?>

<script>
  const avatarUpload = document.getElementById('avatarUpload');
  const avatarPreview = document.getElementById('avatarPreview');

  avatarUpload.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        avatarPreview.src = e.target.result;
      }
      reader.readAsDataURL(file);
    }
  });

  // Fonction pour gérer le téléchargement de l'avatar (à implémenter plus tard)
  function uploadAvatar(file) {
    const formData = new FormData();
    formData.append('avatar', file);
  }
</script>

</body>
</html>