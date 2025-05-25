<?php
require_once __DIR__ . '/../models/Admodel.php';
session_start();
$user = $_SESSION['user'] ?? null;
$userId = $user['id'] ?? null;

if (!$userId) {
    header('Location: /views/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $prix = $_POST['prix'] ?? 0;
    $rue = $_POST['rue'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $codepostal = $_POST['codepostal'] ?? '';
    $type = $_POST['type'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // 处理图片上传
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $fileName;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $imagePath = 'uploads/' . $fileName;
        } else {
            $_SESSION['error_message'] = 'Erreur lors de l\'upload de l\'image.';
            header('Location: /views/mes_annonces.php');
            exit;
        }
    } else {
        $_SESSION['error_message'] = 'Aucune image n\'a été uploadée.';
        header('Location: /views/mes_annonces.php');
        exit;
    }
    
    // 插入新房源到数据库
    global $pdo;
    $sql = "INSERT INTO annonce (Titre, Prix, rue, ville, codepostal, Type, Descriptions, IdProprietaire) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$titre, $prix, $rue, $ville, $codepostal, $type, $description, $userId]);
    
    // 新增：获取新插入的IdAnnonce
    $idAnnonce = $pdo->lastInsertId();
    
    // 新增：插入图片路径到 photoannonce 表
    $sqlPhoto = "INSERT INTO PhotoAnnonce (chemin, IdAnnonce) VALUES (?, ?)";
    $stmtPhoto = $pdo->prepare($sqlPhoto);
    $stmtPhoto->execute([$imagePath, $idAnnonce]);
    
    $_SESSION['success_message'] = 'Annonce publiée avec succès!';
    header('Location: /views/mes_annonces.php');
    exit;
} else {
    header('Location: /views/mes_annonces.php');
    exit;
} 