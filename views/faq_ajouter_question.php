<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Accès refusé']);
        exit;
    }

    $question = trim($_POST['question'] ?? '');
    $reponse = trim($_POST['reponse'] ?? '');

    if (empty($question) || empty($reponse)) {
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
        exit;
    }

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=home4student', 'root', 'root');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO faq (question, reponse) VALUES (?, ?)");
        $stmt->execute([$question, $reponse]);

        echo json_encode([
            'success' => true,
            'message' => 'Question ajoutée avec succès.',
            'question' => htmlspecialchars($question),
            'reponse' => nl2br(htmlspecialchars($reponse))
        ]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Erreur DB : ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
}
?>
