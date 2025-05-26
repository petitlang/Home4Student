<?php
session_start(); // 确保session已启动
/**
 * ad.php — Page de détail pour une annonce
 * Reçoit ?id=xx (optionnellement ?action=show) depuis search.php.
 * Récupère la ligne via ad_get() puis affiche les infos.
 *
 * Emplacement conseillé : /views/ad.php
 */

require_once __DIR__ . '/../models/Admodel.php';
require_once __DIR__ . '/../models/FavorisModel.php';
require_once __DIR__ . '/../models/UserModel.php';

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

$user = $_SESSION['user'] ?? null;
$userId = $user['id'] ?? null;
$role = $_SESSION['role'] ?? null; // 不要默认'etudiant'，以免误判
$isFavoris = $userId ? is_favoris($userId, $id, $role) : false;
$favorisCount = $userId ? get_favoris_count($userId, $role) : 0;


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
$ownerInfo = UserModel::getProprietaireById($IdProp);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $Titre ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="/views/ad.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <link rel="stylesheet" href="/views/ad.css">
</head>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    const adresse = <?= json_encode($adresse) ?>;

    var map = L.map('map').setView([48.8566, 2.3522], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(adresse)}`)
      .then(response => response.json())
      .then(data => {
        if (data.length > 0) {
          var lat = parseFloat(data[0].lat);
          var lon = parseFloat(data[0].lon);
          map.setView([lat, lon], 15);
          L.marker([lat, lon]).addTo(map)
            .bindPopup(adresse).openPopup();
        } else {
          console.warn("Adresse non trouvée :", adresse);
          document.getElementById('map').innerHTML = "Adresse non trouvée.";
        }
      })
      .catch(err => {
        console.error("Erreur lors du géocodage :", err);
        document.getElementById('map').innerHTML = "Erreur lors du chargement de la carte.";
      });
  });
</script>

<body>
<?php include __DIR__ . '/header.php'; ?>
<div class="main-container">
    <!-- Galerie d'images (réelles) -->
    <div class="gallery-and-actions">
      <div class="gallery">
        <?php if ($photos): ?>
          <img src="/<?= htmlspecialchars($photos[0], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
               alt="Photo annonce">
          <?php if (count($photos) > 1): ?>
            <div style="display:grid; gap:1rem;">
              <?php foreach (array_slice($photos, 1) as $p): ?>
                <img src="/<?= htmlspecialchars($p, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                     alt="Photo annonce miniature">
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <img src="https://picsum.photos/800/600?grayscale&random=1" alt="Placeholder">
        <?php endif; ?>
      </div>
      <div class="action-box">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
          <div class="price"><?= $Prix ?> € / mois</div>
          <?php if ($role === 'admin' || $role === 'proprietaire'): ?>
            <a href="/views/edit_ad.php?id=<?= $id ?>" class="btn-admin-action" style="background: #f97316; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; margin-right: 10px; transition: all 0.3s ease; border: none; cursor: pointer;">Editer</a>
            <form method="post" action="/controllers/DeleteAdController.php" style="display:inline;">
              <input type="hidden" name="id_annonce" value="<?= $id ?>">
              <button type="submit" class="btn-admin-action" style="background:#38a169;" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette annonce ?');">Supprimer</button>
            </form>
          <?php endif; ?>
        </div>
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
        <?php if ($role === 'etudiant'): ?>
          <a href="/views/signalement.php?id_annonce=<?= $id ?>" class="btn-favoris add" style="text-decoration: none; margin-top: 1rem; display: block; text-align: center;">
            <i class="fas fa-flag"></i>
            Signaler
          </a>
        <?php endif; ?>
      </div>
    </div>
</div>

<!-- Détails de l'annonce -->
<div class="main-container" style="margin-top:2rem;">
  <div class="info-section">
    <h1><?= $Titre ?></h1>
    <div class="address"><?= $adresse ?></div>
    <div id="map" style="height: 400px; margin-top: 1em;"></div>

    <div class="details-row">
      <div><?= $Type ?></div>
      <div><?= $Etat ?></div>
    </div>

    <div class="description">
      <?= nl2br($Descriptions) ?>
    </div>

    <div class="owner">
      <?php if ($ownerInfo): ?>
        <p><span>Propriétaire :</span> <?= htmlspecialchars($ownerInfo['nom'] . ' ' . $ownerInfo['prenom']) ?></p>
        <p><span>Email :</span> <?= htmlspecialchars($ownerInfo['Email']) ?></p>
        <p><span>Téléphone :</span> <?= htmlspecialchars($ownerInfo['Tele']) ?></p>
      <?php else: ?>
        <p>Informations du propriétaire indisponibles.</p>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/footer.html'; ?>
</body>
</html>
