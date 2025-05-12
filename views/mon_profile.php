<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: /views/login.php');
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
  <style>
    :root {
      --primary: #2ecc71;
      --secondary: #2ecc71;
      --gray: #666;
      --light-gray: #f7f7f7;
      --white: #fff;
      --danger: #e74c3c;
      --warning: #f1c40f;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: var(--light-gray);
      color: #333;
    }

    header {
      background: var(--white);
      padding: 1rem 2rem;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header-title {
      font-size: 1.5rem;
      font-weight: bold;
      color: var(--primary);
    }

    .header-actions {
      display: flex;
      gap: 1rem;
    }

    .container {
      display: flex;
      flex-wrap: wrap;
      padding: 2rem;
      gap: 2rem;
      justify-content: center;
      max-width: 1200px;
      margin: 0 auto;
    }

    .card {
      background: var(--white);
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      padding: 2rem;
      flex: 1 1 320px;
      min-width: 300px;
      max-width: 500px;
      position: relative;
      transition: transform 0.2s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .avatar-container {
      position: relative;
      width: 100px;
      height: 100px;
      margin: 0 auto 1rem;
    }

    .avatar {
      width: 100%;
      height: 100%;
      background: var(--primary);
      color: white;
      font-size: 2.5rem;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      overflow: hidden;
      position: relative;
      cursor: pointer;
      transition: opacity 0.2s;
    }

    .avatar:hover {
      opacity: 0.9;
    }

    .avatar img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 50%;
    }

    .avatar-upload {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: rgba(0,0,0,0.5);
      color: white;
      text-align: center;
      padding: 0.3rem;
      font-size: 0.8rem;
      opacity: 0;
      transition: opacity 0.2s;
    }

    .avatar-container:hover .avatar-upload {
      opacity: 1;
    }

    .avatar input[type="file"] {
      display: none;
    }

    .name {
      font-size: 1.5rem;
      font-weight: bold;
      margin: 0.5rem 0;
      text-align: center;
    }

    .role {
      color: var(--gray);
      font-size: 1rem;
      margin-bottom: 1rem;
      text-align: center;
    }

    .joined {
      font-size: 0.9rem;
      color: var(--gray);
      margin-top: 0.5rem;
      text-align: center;
    }

    .info {
      margin-top: 2rem;
    }

    .info-title {
      font-weight: bold;
      margin-bottom: 1rem;
      font-size: 1.1rem;
      color: var(--primary);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .info-item {
      margin: 0.8rem 0;
      display: flex;
      align-items: center;
      gap: 0.8rem;
      font-size: 0.95rem;
      padding: 0.5rem;
      border-radius: 8px;
      transition: background-color 0.2s;
    }

    .info-item:hover {
      background-color: var(--light-gray);
    }

    .info-item i {
      color: var(--primary);
      width: 20px;
      text-align: center;
    }

    .verification-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.3rem 0.8rem;
      border-radius: 20px;
      font-size: 0.9rem;
      margin-left: 0.5rem;
    }

    .verified {
      background-color: var(--primary);
      color: white;
    }

    .unverified {
      background-color: var(--warning);
      color: #333;
    }

    .btn {
      background: var(--primary);
      color: white;
      padding: 0.8rem 1.5rem;
      font-size: 1rem;
      border: none;
      border-radius: 30px;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
      text-align: center;
      transition: background-color 0.2s;
      display: inline-block;
    }

    .btn:hover {
      background-color: #27ae60;
    }

    .btn-secondary {
      background: var(--secondary);
    }

    .btn-secondary:hover {
      background-color: #27ae60;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      margin-top: 2rem;
    }

    .stat-item {
      text-align: center;
      padding: 1rem;
      background: var(--light-gray);
      border-radius: 12px;
    }

    .stat-value {
      font-size: 1.5rem;
      font-weight: bold;
      color: var(--primary);
    }

    .stat-label {
      font-size: 0.9rem;
      color: var(--gray);
      margin-top: 0.3rem;
    }

    @media(max-width: 768px) {
      .container {
        flex-direction: column;
        align-items: center;
      }
      
      .stats {
        grid-template-columns: 1fr;
      }
    }

    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
      align-items: center;
    }

    .btn-action {
      width: 90%;
      max-width: 350px;
      margin: 0 auto;
      background: var(--primary);
      color: #fff;
      border: none;
      border-radius: 30px;
      padding: 1rem 0;
      font-size: 1.1rem;
      font-weight: bold;
      text-align: center;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.7rem;
      transition: background 0.2s;
    }

    .btn-action:hover {
      background: #27ae60;
    }
  </style>
</head>
<body>

<header>
  <div class="header-title">SeLogerFacilement Profil</div>
  <div class="header-actions">
    <a href="/views/index2.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i>Page d'accueil</a>
  </div>
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
        <a href="/views/profile-edit.php" class="btn btn-action"><i class="fas fa-user-check"></i> Compléter mon profil</a>
        <a href="/views/candidature.php" class="btn btn-action"><i class="fas fa-file-alt"></i> Mes Candidatures</a>
        
      <?php elseif ($role === 'proprietaire'): ?>
        <div class="info">
          <div class="info-title">
            <i class="fas fa-id-card"></i>
            Compléter mon profil
          </div>
        </div>
        <a href="/views/profile-edit.php" class="btn btn-action"><i class="fas fa-user-check"></i> Compléter mon profil</a>
        <a href="/views/annonces.php" class="btn btn-action"><i class="fas fa-home"></i> Mes Annonces</a>
      <?php endif; ?>
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
      <a href="/views/chat.php" class="btn btn-action"><i class="fas fa-comments"></i> Chat</a>
      <?php endif; ?>
    </div>
  </div>
</div>

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
