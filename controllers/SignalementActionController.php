<?php
session_start();
require_once __DIR__ . '/../models/connection.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: /views/login.html');
    exit();
}

$action = $_POST['action'] ?? '';
$id = $_POST['id'] ?? '';

if ($action === 'mark_done' && $id) {
    // 用status字段标记为已处理（法语）
    $stmt = $pdo->prepare("UPDATE signaler SET status = 'Traité' WHERE IdSignaler = ?");
    $stmt->execute([$id]);
} elseif ($action === 'delete' && $id) {
    $stmt = $pdo->prepare("DELETE FROM signaler WHERE IdSignaler = ?");
    $stmt->execute([$id]);
}
header('Location: /views/signalements_list.php');
exit; 