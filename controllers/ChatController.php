<?php
session_start();
require_once '../models/ChatModel.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

$user = $_SESSION['user'];
$role = $_SESSION['role'];
$userId = ($role === 'etudiant') ? $user['IdEtudiant'] : $user['IdPropietaire'];

header('Content-Type: application/json');

if ($_GET['action'] === 'load') {
    $otherId = $_GET['with'];
    $otherRole = $_GET['role'];

    $messages = ChatModel::getMessages($userId, $role, $otherId, $otherRole);
    echo json_encode($messages);
    exit;
}

if ($_GET['action'] === 'send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';
    $toId = $_POST['to_id'];
    $toRole = $_POST['to_role'];

    if (trim($message) === '') {
        echo json_encode(['success' => false, 'error' => 'Message vide']);
        exit;
    }

    $success = ChatModel::envoyerMessage($message, $userId, $role, $toId, $toRole);
    echo json_encode(['success' => $success]);
    exit;
}
