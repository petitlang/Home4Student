<?php
require_once __DIR__.'/../models/ChatModel.php';

session_start();
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? null;
switch ($action) {

    /* ---------- 1. 左侧联系人列表 ---------- */
    case 'getNav':
        // 来自 form.html 的第一次 POST 会带 id/role
        $me = $_POST['id'] ?? $_SESSION['user_id'] ?? null;
        if (!$me) {
            http_response_code(400);
            exit(json_encode(['error'=>'missing id']));
        }
        $_SESSION['expediteur'] = $me;                 // 记 session
        $nav = ChatModel::getNav($me);

        $_SESSION['chatList'] = $nav;

        header('Location: ../views/chat.php');

        exit;

    /* ---------- 2. 取聊天记录 ---------- */
    case 'getMessages':
        $idMsg = intval($_GET['idMsg'] ?? 0);
        if (!$idMsg) {
            http_response_code(400);
            exit(json_encode(['error'=>'missing idMsg']));
        }
        exit(json_encode(ChatModel::getMessages($idMsg)));

    /* ---------- 3. 发送消息 ---------- */
    case 'sendMessage':
        $payload     = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $idMsg       = intval($payload['idMsg']       ?? 0);
        $message     = trim($payload['message']       ?? '');
        $expediteur  = $payload['expediteur']         ?? ($_SESSION['user_id'] ?? null);
        $destinateur = $payload['destinateur']        ?? null;

        if (!$idMsg || !$expediteur || !$destinateur || $message==='') {
            http_response_code(400);
            exit(json_encode(['error'=>'missing fields']));
        }
        ChatModel::envoyerMessage($idMsg, $message, $expediteur, $destinateur);
        exit(json_encode(['success'=>true]));

    default:
        http_response_code(400);
        exit(json_encode(['error'=>'unknown action']));
}
