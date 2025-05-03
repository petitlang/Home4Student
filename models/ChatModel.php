<?php
require_once __DIR__.'/1/connection.php';  // 记得把 PDO 连接放在同级

class ChatModel
{
    /**
     * 返回“我”所有会话的联系人列表
     * 逻辑：同一个 IdMsg 视为一条会话；取出与我关联的全部 IdMsg 及对方名字
     */
    public static function getNav(string $expediteur): array
    {
        global $pdo;
        $query = "SELECT distinct idmsg, destinateur FROM chat WHERE Expediteur = :expediteur";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':expediteur'=>$expediteur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** 取出某条会话的全部消息 */
    public static function getMessages(int $idMsg): array
    {
        global $pdo;
        $sql = "
            SELECT message, expediteur, temps
            FROM   Chat
            WHERE  IdMsg = :id
            ORDER  BY temps ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id'=>$idMsg]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** 写入一条新消息 */
    public static function envoyerMessage(int $idMsg, string $message,
                                          string $expediteur, string $destinateur): bool
    {
        global $pdo;
        $sql = "INSERT INTO Chat (IdMsg, message, temps, expediteur, destinateur)
                VALUES (:id, :msg, NOW(), :exp, :dst)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'  => $idMsg,
            ':msg' => $message,
            ':exp' => $expediteur,
            ':dst' => $destinateur
        ]);
    }
}
