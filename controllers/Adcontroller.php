<?php
require_once __DIR__ . '/../config/database.php'; // -> renvoie un objet PDO $pdo
require_once __DIR__ . '/../models/AdModel.php';

header('Content‑Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? 'search';
$model  = new AdModel($pdo);

switch ($action) {

    case 'find':
        $id   = (int) ($_GET['id'] ?? 0);
        $data = $model->find($id);
        echo json_encode([
            'success' => (bool) $data,
            'data'    => $data,
        ]);
        break;

    case 'search':
    default:
        // filtres transmis en GET (ex. ?ville=Paris&type=Studio&min=400&max=900&offset=0&limit=8)
        $ville   = $_GET['ville']  ?? null;
        $type    = $_GET['type']   ?? null;
        $min     = ($_GET['min']   !== '') ? (int) $_GET['min']   : null;
        $max     = ($_GET['max']   !== '') ? (int) $_GET['max']   : null;
        $offset  = (int) ($_GET['offset']  ?? 0);
        $limit   = (int) ($_GET['limit']   ?? 8);

        $ads = $model->search($ville, $type, $min, $max, $offset, $limit);

        echo json_encode([
            'success' => true,
            'data'    => $ads,
        ]);
}
