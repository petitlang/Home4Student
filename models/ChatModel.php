<?php
require_once 'connection.php';

class ChatModel {


    public static function getMessages($userId, $userRole, $otherId, $otherRole) {
        global $pdo;

        $query = "
            SELECT * FROM Chat
            WHERE 
                (expediteur = :userId AND expediteur_role = :userRole 
                 AND destinateur = :otherId AND destinateur_role = :otherRole)
            OR 
                (expediteur = :otherId AND expediteur_role = :otherRole 
                 AND destinateur = :userId AND destinateur_role = :userRole)
            ORDER BY temps ASC
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'userId' => $userId,
            'userRole' => $userRole,
            'otherId' => $otherId,
            'otherRole' => $otherRole
        ]);
        return $stmt->fetchAll();
    }

    // 发送新消息
    public static function envoyerMessage($message, $fromId, $fromRole, $toId, $toRole) {
        global $pdo;

        $stmt = $pdo->prepare("
            INSERT INTO Chat (message, expediteur, expediteur_role, destinateur, destinateur_role)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$message, $fromId, $fromRole, $toId, $toRole]);
    }
}
