<?php
/**
 * ad.php — Page de détail pour une annonce
 * Reçoit ?id=xx (optionnellement ?action=show) depuis search.html.
 * Récupère la ligne via ad_get() puis affiche les infos.
 *
 * Emplacement conseillé : /views/ad.php
 */

require_once __DIR__ . '/../models/Admodel.php';

// 1️⃣ Paramètres GET -----------------------------------------------------------
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

// 2️⃣ Préparation des variables "safe" ---------------------------------------
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
  <style>
    :root {
      --primary: #2ecc71;
      --secondary: #e74c3c;
      --accent: #3498db;
      --dark: #2c3e50;
      --light: #ecf0f1;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }

    body {
      background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
      url('https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=1350&q=80');
      background-size: cover; background-position: center; color: #fff; min-height: 100vh;
    }

    header { padding: 1.5rem 3rem; background: rgba(44,62,80,0.95); display:flex; justify-content:space-between; align-items:center; }
    .logo { font-size:2rem; color:var(--primary); font-weight:bold; }

    .main-container { max-width:1200px; margin:2rem auto; display:flex; gap:2rem; padding:0 2rem; }
    .gallery { flex:2; display:grid; grid-template-columns:2fr 1fr; gap:1rem; }
    .gallery img { width:100%; border-radius:10px; object-fit:cover; }

    .info-section { flex:3; background:rgba(255,255,255,0.95); color:#333; border-radius:10px; padding:2rem; }
    .info-section h1 { font-size:2rem; color:var(--dark); margin-bottom:0.5rem; }
    .info-section .address { font-style:italic; color:#666; margin-bottom:1rem; }
    .details-row { display:flex; gap:2rem; margin-bottom:1rem; }
    .details-row div { font-weight:bold; }
    .description { margin:2rem 0; line-height:1.6; }
    .owner { margin-top:2rem; padding-top:1rem; border-top:1px solid #ddd; }
    .owner span { font-weight:bold; }

    .action-box { flex:1.2; background: rgb(255, 255, 255); padding:2rem; border-radius:10px; height:fit-content; display:flex; flex-direction:column; justify-content:space-between; }
    .price { font-size:1.5rem; font-weight:bold; color:var(--dark); margin-bottom:1rem; }
    .date-select, .people-select { color:var(--dark);margin-bottom:1rem; }
    .date-select input, .people-select input { width:100%; padding:0.8rem; border-radius:5px; border:1px solid #000000; font-size:1rem; }
    .btn { padding:1rem; border:none; border-radius:25px; font-weight:bold; cursor:pointer; transition:opacity 0.3s; }
    .btn-primary { background:var(--primary); color:white; margin-bottom:0.5rem; }
    .btn-primary:hover { opacity:0.85; }
    .btn-accent { background:var(--accent); color:white; }
    .btn-accent:hover { opacity:0.85; }
  </style>
</head>
<body>
<header>
  <div class="logo">SeLogerFacilement</div>
</header>

<div class="main-container">
    <!-- Galerie d'images (réelles) -->
    <div class="gallery">
        <?php if ($photos): ?>
            <!-- ① photo principale -->
            <img src="/<?= htmlspecialchars($photos[0], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                 alt="Photo annonce">

            <!-- ② miniatures supplémentaires -->
            <?php if (count($photos) > 1): ?>
                <div style="display:grid; gap:1rem;">
                    <?php foreach (array_slice($photos, 1) as $p): ?>
                        <img src="/<?= htmlspecialchars($p, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"
                             alt="Photo annonce miniature">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Fallback : si aucune image n’est trouvée -->
            <img src="https://picsum.photos/800/600?grayscale&random=1" alt="Placeholder">
        <?php endif; ?>
    </div>

  <!-- Boîte d'action (prix + candidature) -->
  <div class="action-box">
    <div class="price"><?= $Prix ?> € / mois</div>
    <div class="date-select">
      <label>Date de début</label>
      <input type="date" />
    </div>
    <div class="date-select">
      <label>Date de fin</label>
      <input type="date" />
    </div>
    <div class="people-select">
      <label>Nombre de personnes</label>
      <input type="number" min="1" max="10" />
    </div>
    <button class="btn btn-primary">Candidat(e)</button>
    <button class="btn btn-accent">Ajouter aux favoris</button>
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
</body>
</html>
