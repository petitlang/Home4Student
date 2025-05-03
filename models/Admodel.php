<?php
// get_annonces.php
require './1/connection.php';      // 复用你已写好的 PDO 连接 :contentReference[oaicite:0]{index=0}:contentReference[oaicite:1]{index=1}
header('Content-Type: application/json; charset=utf-8');

// ❶ 如果你的表就叫 “annonce”——注意大小写与复数——直接查。
//    若叫别的名字，改成对应表名即可。
$sql = 'SELECT IdAnnonce, Titre, Type, Prix, Etat,
               rue, codepostal, ville, Pays,
               Descriptions, IdProprietaire
        FROM annonce';

$stmt  = $pdo->query($sql);
$rows  = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ❷ 输出 JSON（保持法语重音、不转义斜杠）
echo json_encode($rows, JSON_UNESCAPED_UNICODE);
?>
