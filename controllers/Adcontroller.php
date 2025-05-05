<?php
// ────────────────────────────────────────────────────────────────
//  AdController.php — Router / Front‑controller for annonces
//  • action=list            → JSON list (used by search.html fetch)
//  • action=show&id=xx      → HTTP 302 → /views/ad.php?id=xx
// ────────────────────────────────────────────────────────────────

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/../models/Admodel.php';

function jsonResponse(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_GET['action'] ?? 'list';
$id     = isset($_GET['id']) ? (int)$_GET['id'] : null;

switch ($action) {

    // ---------------------------------------------------------------- list ---
    case 'list':
        jsonResponse(ad_list());              // → JSON pour search.html
        break;

    // ---------------------------------------------------------------- show ---
    case 'show':
        if (!$id) {
            jsonResponse(['error' => 'Missing parameter: id'], 400);
        }

        // Vérification simple : s'assure que l'annonce existe avant de rediriger.
        if (!ad_get($id)) {                   // retourne null si pas trouvé
            jsonResponse(['error' => 'Annonce not found'], 404);
        }

        // Redirige vers la vue (ad.php s'occupe du rendu final)
        header('Location: ../views/ad.php?id=' . $id);
        exit;                                 // stopper le script

    // ----------------------------------------------------------- invalid ---
    default:
        jsonResponse(['error' => 'Invalid action'], 400);
}
?>
