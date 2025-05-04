<?php
// ────────────────────────────────────────────────────────────────
//  AdController.php  ─ MVC controller for annonces
//  Handles API‑style requests coming from search.html and ad.html.
//  Expected routes (via GET parameters):
//      • action=list               → returns full list (delegates to Admodel.php)
//      • action=show&id=<integer>  → returns one annonce in JSON
//  ----------------------------------------------------------------
//  Folder layout (suggested):
//      /controllers/AdController.php        <‑‑ (this file)
//      /models/Admodel.php
//      /models/connection.php
//      /views/search.html
//      /views/ad.html
// ────────────────────────────────────────────────────────────────

// Allow cross‑origin calls from the static html pages (optional)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle CORS pre‑flight quickly
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Bring in the PDO connection shared by all models
require_once __DIR__ . '/../models/1/connection.php';  // adjust if your tree differs

/**
 * Convenience: always answer with JSON and halt.
 */
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
    // ───────────────────────────────────────────────── list ──
    case 'list':
        // We already have a model that streams the whole table as JSON.
        // Simply delegate to it and stop further execution.
        require_once __DIR__ . '/../models/Admodel.php';   // Admodel echoes JSON itself
        exit; // SAFETY: make sure nothing below runs

    // ───────────────────────────────────────────────── show ──
    case 'show':
        if (!$id) {
            jsonResponse(['error' => 'Missing parameter: id'], 400);
        }

        // Fetch the annonce whose primary key = :id
        $stmt = $pdo->prepare(
            'SELECT IdAnnonce, Titre, Type, Prix, Etat,
                    rue, codepostal, ville, Pays,
                    Descriptions, IdProprietaire
             FROM   annonce
             WHERE  IdAnnonce = :id'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            jsonResponse(['error' => 'Annonce not found'], 404);
        }

        jsonResponse($row); // <- 🚀 success
        break;

    // ─────────────────────────────────────────────── default ──
    default:
        jsonResponse(['error' => 'Invalid action'], 400);
}
?>
