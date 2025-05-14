<?php
// ────────────────────────────────────────────────────────────────
//  models/Admodel.php — Data‑access layer for table `annonce`
//  Exposes two functions and (optionally) acts as a tiny JSON API
//      • ad_list()          → array<annonce>
//      • ad_get(int $id)    → array|null
//
//  If the file is requested directly in the browser (not included)
//  it will output JSON (all annonces or a single one when ?id=xx).
// ────────────────────────────────────────────────────────────────

require_once __DIR__ . '/1/connection.php';   // initialise $pdo (PDO instance)

/**
 * Fetch all annonces from DB.
 * @return array<int,array<string,mixed>>
 */
function ad_list(): array
{
    global $pdo;
    $sql = 'SELECT IdAnnonce, Titre, Type, Prix, Etat,
                   rue, codepostal, ville, Pays,
                   Descriptions, IdProprietaire
            FROM annonce';
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Fetch one annonce by primary key IdAnnonce.
 * @param  int   $id
 * @return array<string,mixed>|null  the row or null when not found
 */
function ad_get(int $id): ?array
{
    global $pdo;
    $stmt = $pdo->prepare(
        'SELECT IdAnnonce, Titre, Type, Prix, Etat,
                rue, codepostal, ville, Pays,
                Descriptions, IdProprietaire
         FROM   annonce
         WHERE  IdAnnonce = :id'
    );
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

function ad_photos(int $id): array
{
    global $pdo;
    $stmt = $pdo->prepare(
        'SELECT chemin
           FROM PhotoAnnonce         -- ⚠️  确认表名与字段大小写
          WHERE IdAnnonce = :id
          ORDER BY IdPhoto'
    );
    $stmt->execute([':id' => $id]);
    // fetchAll(PDO::FETCH_COLUMN) → renvoie un tableau simple de chaînes
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

/**
 * Fetch annonces by owner (propriétaire) with pagination.
 * @param int $ownerId
 * @param int $page
 * @param int $perPage
 * @return array
 */
function get_owner_ads(int $ownerId, int $page = 1, int $perPage = 9): array
{
    global $pdo;
    $offset = ($page - 1) * $perPage;
    // Count total
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM annonce WHERE IdProprietaire = :ownerId');
    $stmt->execute([':ownerId' => $ownerId]);
    $total = (int)$stmt->fetchColumn();
    $totalPages = $perPage > 0 ? ceil($total / $perPage) : 1;
    $page = max(1, min($page, $totalPages));
    $offset = ($page - 1) * $perPage;
    // Get paginated data
    $stmt = $pdo->prepare('SELECT IdAnnonce, Titre, Type, Prix, Etat, rue, codepostal, ville, Pays, Descriptions, IdProprietaire FROM annonce WHERE IdProprietaire = :ownerId ORDER BY IdAnnonce DESC LIMIT :perPage OFFSET :offset');
    $stmt->bindValue(':ownerId', $ownerId, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $ads = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return [
        'ads' => $ads,
        'total' => $total,
        'totalPages' => $totalPages,
        'currentPage' => $page,
        'perPage' => $perPage
    ];
}

// ────────────────────────────────
//  Tiny JSON API fallback.
//  Only runs when this file is *directly* requested, not when included.
// ────────────────────────────────
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: application/json; charset=utf-8');

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

    if ($id) {
        $row = ad_get($id);
        if ($row) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Annonce not found'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        echo json_encode(ad_list(), JSON_UNESCAPED_UNICODE);
    }
    exit;
}
?>
