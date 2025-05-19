<?php
require_once __DIR__.'/1/connection.php';  // 记得把 PDO 连接放在同级

class ChatModel
{
    /**
     * 返回"我"所有会话的联系人列表
     * 逻辑：同一个 IdMsg 视为一条会话；取出与我关联的全部 IdMsg 及对方名字
     */
    public static function getNav(string $expediteur): array
    {
        global $pdo;
        $query = "SELECT distinct idchat, destinateur FROM message WHERE expediteur = :expediteur";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':expediteur'=>$expediteur]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** 取出某条会话的全部消息 */
    public static function getMessages(int $idChat): array
    {
        global $pdo;
        $sql = "
            SELECT message, expediteur, temps
            FROM   message
            WHERE  IdChat = :id
            ORDER  BY temps ASC
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id'=>$idChat]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** 写入一条新消息 */
    public static function envoyerMessage(int $idChat, string $message,
                                          string $expediteur, string $destinateur): bool
    {
        global $pdo;
        $sql = "INSERT INTO message (IdChat, message, temps, expediteur, destinateur)
                VALUES (:id, :msg, NOW(), :exp, :dst)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':id'  => $idChat,
            ':msg' => $message,
            ':exp' => $expediteur,
            ':dst' => $destinateur
        ]);
    }

    /** 创建新的聊天会话 */
    public static function createChat(string $expediteur, string $destinateur): int
    {
        global $pdo;
        try {
            // 开始事务
            $pdo->beginTransaction();
            
            // 生成新的IdChat
            $sql = "SELECT COALESCE(MAX(IdChat), 0) + 1 as newId FROM message";
            $stmt = $pdo->query($sql);
            $newIdChat = $stmt->fetch(PDO::FETCH_ASSOC)['newId'];
            
            // 插入一条初始消息（发起方）
            $sql = "INSERT INTO message (IdChat, message, temps, expediteur, destinateur)
                    VALUES (:id, :msg, NOW(), :exp, :dst)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id'  => $newIdChat,
                ':msg' => 'Conversation démarrée',
                ':exp' => $expediteur,
                ':dst' => $destinateur
            ]);
            // 插入一条初始消息（收件人）
            $stmt->execute([
                ':id'  => $newIdChat,
                ':msg' => 'Conversation démarrée',
                ':exp' => $destinateur,
                ':dst' => $expediteur
            ]);
            
            // 提交事务
            $pdo->commit();
            return $newIdChat;
            
        } catch (Exception $e) {
            // 如果出错，回滚事务
            $pdo->rollBack();
            throw $e;
        }
    }
}
