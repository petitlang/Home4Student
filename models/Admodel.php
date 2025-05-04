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
