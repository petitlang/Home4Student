<?php
/**
 * ad.php — Page de détail pour une annonce
 * Reçoit ?id=xx (optionnellement ?action=show) depuis search.html.
 * Récupère la ligne via ad_get() puis affiche les infos.
 *
 * Emplacement conseillé : /views/ad.php
 */

require_once __DIR__ . '/../models/Admodel.php';
require_once __DIR__ . '/../models/FavorisModel.php';

// 1️⃣ Paramètres GET -----------------------------------------------------------
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if (!$id) {
    http_response_code(400);
    exit('Paramètre "id" manquant');
}

$ad = ad_get($id);
$photos  = ad_photos($id);
if (!$ad) {
    http_response_code(404);
    exit('Annonce introuvable');
}

// 检查用户是否已登录
session_start();
$user = $_SESSION['user'] ?? null;
$userId = $user['id'] ?? null;
$isFavoris = $userId ? is_favoris($userId, $id) : false;
$favorisCount = $userId ? get_favoris_count($userId) : 0;

// 2️⃣ Préparation des variables "safe" ---------------------------------------
$Titre        = htmlspecialchars($ad['Titre']        ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$Prix         = htmlspecialchars($ad['Prix']         ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$Type         = htmlspecialchars($ad['Type']         ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$Etat         = htmlspecialchars($ad['Etat']         ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$rue          = htmlspecialchars($ad['rue']          ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$postal       = htmlspecialchars($ad['codepostal']   ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$ville        = htmlspecialchars($ad['ville']        ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$pays         = htmlspecialchars($ad['Pays']         ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$Descriptions = htmlspecialchars($ad['Descriptions'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
$IdProp       = (int)($ad['IdProprietaire']          ?? 0);

$adresse      = trim("$rue, $postal $ville, $pays", ', ');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $Titre ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="/views/ad.css" />
  <style>
    .btn-favoris {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s ease;
      width: 100%;
      justify-content: center;
      margin-top: 1rem;
    }
    .btn-favoris.add {
      background-color: #e53e3e;
      color: white;
      box-shadow: 0 2px 4px rgba(229, 62, 62, 0.2);
    }
    .btn-favoris.add:hover {
      background-color: #c53030;
      transform: translateY(-1px);
      box-shadow: 0 4px 6px rgba(229, 62, 62, 0.3);
    }
    .btn-favoris.remove {
      background-color: #718096;
      color: white;
      box-shadow: 0 2px 4px rgba(113, 128, 150, 0.2);
    }
    .btn-favoris.remove:hover {
      background-color: #4a5568;
      transform: translateY(-1px);
      box-shadow: 0 4px 6px rgba(113, 128, 150, 0.3);
    }
    .btn-favoris i {
      font-size: 1.1rem;
    }
    .btn-favoris:active {
      transform: translateY(0);
    }
  </style>
</head>
<body>
  <header>
    <nav class="navbar">
      <div class="logo">
        <img src="/views/logo-removebg-preview.png" alt="logo" />
      </div>
      <div class="nav-center">
        <div class="nav-links">
          <a href="/views/ads_list.php">Offres</a>
          <a href="/views/chat.php">Messagerie</a>
          <a href="/views/favoris.php" class="relative">
            Favoris
            <?php if ($userId && $favorisCount > 0): ?>
              <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                <?php echo $favorisCount; ?>
              </span>
            <?php endif; ?>
          </a>
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
<div class="main-container">
    <!-- Galerie d'images (réelles) -->
    <div class="gallery">
        <?php if ($photos): ?>
            <!-- ① photo principale -->
            <img src="/<?= htmlspecialchars($photos[0], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                 alt="Photo annonce">

            <!-- ② miniatures supplémentaires -->
            <?php if (count($photos) > 1): ?>
                <div style="display:grid; gap:1rem;">
                    <?php foreach (array_slice($photos, 1) as $p): ?>
                        <img src="/<?= htmlspecialchars($p, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                             alt="Photo annonce miniature">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Fallback : si aucune image n'est trouvée -->
            <img src="https://picsum.photos/800/600?grayscale&random=1" alt="Placeholder">
        <?php endif; ?>
    </div>

  <!-- Boîte d'action (prix + candidature) -->
  <div class="action-box">
    <div class="price"><?= $Prix ?> € / mois</div>
    <form method="post" action="/controllers/CandidatureController.php" style="display:flex;flex-direction:column;gap:1rem;">
      <input type="hidden" name="id_annonce" value="<?= $id ?>">
      <div class="date-select">
        <label>Date de début</label>
        <input type="date" name="debut" required>
      </div>
      <div class="date-select">
        <label>Date de fin</label>
        <input type="date" name="fin" required>
      </div>
      <div class="people-select">
        <label>Nombre de personnes</label>
        <input type="number" name="nb_personnes" min="1" max="10" required>
      </div>
      <button type="submit" class="btn btn-primary">Candidat(e)</button>
    </form>
    <?php if ($userId): ?>
      <form method="post" action="/controllers/FavorisController.php" style="margin-top: 1rem;">
        <input type="hidden" name="action" value="<?= $isFavoris ? 'remove' : 'add' ?>">
        <input type="hidden" name="id_annonce" value="<?= $id ?>">
        <button type="submit" class="btn-favoris <?= $isFavoris ? 'remove' : 'add' ?>">
          <i class="fas <?= $isFavoris ? 'fa-heart-broken' : 'fa-heart' ?>"></i>
          <?= $isFavoris ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>
        </button>
      </form>
    <?php else: ?>
      <a href="/views/favoris.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>" class="btn-favoris add" style="text-decoration: none; margin-top: 1rem;">
        <i class="fas fa-heart"></i>
        Se connecter pour ajouter aux favoris
      </a>
    <?php endif; ?>
  </div>
</div>

<!-- Détails de l'annonce -->
<div class="main-container" style="margin-top:2rem;">
  <div class="info-section">
    <h1><?= $Titre ?></h1>
    <div class="address"><?= $adresse ?></div>

    <div class="details-row">
      <div><?= $Type ?></div>
      <div><?= $Etat ?></div>
    </div>

    <div class="description">
      <?= nl2br($Descriptions) ?>
    </div>

    <div class="owner">
      <p><span>Id du propriétaire :</span> <?= $IdProp ?></p>
      <!-- Ici vous pouvez faire un JOIN pour récupérer email / tél. si nécessaire -->
    </div>
  </div>
</div>

<footer>
  <div class="container">
      <div class="footer-grid">
          <div>
              <div class="logo text-white mb-4">
                  <i class="fas fa-graduation-cap text-2xl mr-2"></i>
                  <span class="font-bold text-lg">HomeStudent</span>
              </div>
              <div class="social-links">
                  <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                  <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                  <a href="#" class="social-link"><i class="fab fa-facebook"></i></a>
                  <a href="#" class="social-link"><i class="fab fa-linkedin"></i></a>
              </div>
          </div>
          <div>
              <h3 class="footer-heading">L'entreprise</h3>
              <ul class="footer-links">
                  <li class="footer-link"><a href="#">Qui sommes-nous ?</a></li>
                  <li class="footer-link"><a href="/views/contact.html">Nous contacter</a></li>
              </ul>
          </div>
          <div>
              <h3 class="footer-heading">Services pro</h3>
              <ul class="footer-links">
                  <li class="footer-link"><a href="#">Accès client</a></li>
              </ul>
          </div>
          <div>
              <h3 class="footer-heading">À découvrir</h3>
              <ul class="footer-links">
                  <li class="footer-link"><a href="#">Tout l'immobilier</a></li>
                  <li class="footer-link"><a href="#">Toutes les villes</a></li>
                  <li class="footer-link"><a href="#">Tous les départements</a></li>
                  <li class="footer-link"><a href="#">Toutes les régions</a></li>
              </ul>
          </div>
      </div>
      <div class="copyright">
          &copy; 2023 HomeStudent - Se loger Facilement. Tous droits réservés.
      </div>
  </div>
</footer>
</body>
</html>
