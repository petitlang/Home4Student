<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: faq_back.php?error=unauthorized");
        exit;
    }

    $id = intval($_POST['id'] ?? 0);

    if ($id <= 0) {
        header("Location: faq_back.php?error=invalid_id");
        exit;
    }

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=home4student', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("DELETE FROM faq WHERE IdFAQ = ?");
        $stmt->execute([$id]);

        header("Location: faq_back.php?success=deleted");
        exit;
    } catch (PDOException $e) {
        header("Location: faq_back.php?error=db");
        exit;
    }
}
?>
