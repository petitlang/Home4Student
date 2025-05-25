<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: faq_back.php?error=unauthorized");
        exit;
    }

    $id = intval($_POST['id'] ?? 0);
    $question = trim($_POST['question'] ?? '');
    $reponse = trim($_POST['reponse'] ?? '');

    if ($id <= 0 || empty($question) || empty($reponse)) {
        header("Location: faq_back.php?error=invalid");
        exit;
    }

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=home4student', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("UPDATE faq SET question = ?, reponse = ? WHERE IdFAQ = ?");
        $stmt->execute([$question, $reponse, $id]);

        header("Location: faq_back.php?success=update");
        exit;
    } catch (PDOException $e) {
        header("Location: faq_back.php?error=db");
        exit;
    }
}
?>
