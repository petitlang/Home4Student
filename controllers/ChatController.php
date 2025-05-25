<?php
require_once __DIR__.'/../models/ChatModel.php';

session_start();
header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? null;
switch ($action) {

    /* ---------- 1. 左侧联系人列表 ---------- */
    case 'getNav':
        // 优先用POST，否则用SESSION
        $id   = $_POST['id']   ?? ($_SESSION['user']['id']   ?? null);
        $role = $_POST['role'] ?? ($_SESSION['user']['role'] ?? null);

        if (!$id || !$role) {
            http_response_code(400);
            exit(json_encode(['error'=>'missing id or role']));
        }

        $me = $role . '-' . $id; // 组合新id，如 b-13
        $_SESSION['expediteur'] = $me;

        $nav = ChatModel::getNav($me);
        $_SESSION['chatList'] = $nav;

        header('Location: ../views/chat.php');
        exit;

    /* ---------- 2. 取聊天记录 ---------- */
    case 'getMessages':
        $idChat = intval($_GET['idChat'] ?? 0);
        if (!$idChat) {
            http_response_code(400);
            exit(json_encode(['error'=>'missing idChat']));
        }
        exit(json_encode(ChatModel::getMessages($idChat)));

    /* ---------- 3. 发送消息 ---------- */
    case 'sendMessage':
        $payload     = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $idChat      = intval($payload['idChat']      ?? 0);
        $message     = trim($payload['message']       ?? '');
        $expediteur  = $payload['expediteur']         ?? ($_SESSION['user_id'] ?? null);
        $destinateur = $payload['destinateur']        ?? null;

        if (!$idChat || !$expediteur || !$destinateur || $message==='') {
            http_response_code(400);
            exit(json_encode(['error'=>'missing fields']));
        }
        ChatModel::envoyerMessage($idChat, $message, $expediteur, $destinateur);
        exit(json_encode(['success'=>true]));

    /* ---------- 4. 创建会话 ---------- */
    case 'createChat':
        // 优先从POST/json获取参数
        $payload = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $expediteur = $payload['expediteur'] ?? null;
        $destinateur = $payload['destinateur'] ?? null;

        if (!$expediteur || !$destinateur) {
            http_response_code(400);
            exit(json_encode(['error'=>'expéditeur ou destinataire manquant']));
        }

        try {
            $idChat = ChatModel::createChat($expediteur, $destinateur);
            exit(json_encode([
                'success' => true,
                'idChat' => $idChat,
                'message' => 'Conversation créée avec succès'
            ]));
        } catch (Exception $e) {
            http_response_code(500);
            exit(json_encode([
                'error' => 'Échec de la création de la conversation',
                'details' => $e->getMessage()
            ]));
        }

    case 'getUserInfo':
        $data = json_decode(file_get_contents('php://input'), true);
        $role = $data['role'] ?? '';
        $id = $data['id'] ?? '';
        
        if (!$role || !$id) {
            echo json_encode(['success' => false, 'error' => 'Paramètres manquants']);
            return;
        }
        
        try {
            global $pdo;
            if ($role === 'etudiant') {
                $stmt = $pdo->prepare("SELECT CONCAT(prenom, ' ', nom) as fullname FROM Etudiant WHERE IdEtudiant = ?");
            } else {
                $stmt = $pdo->prepare("SELECT CONCAT(prenom, ' ', nom) as fullname FROM Proprietaire WHERE IdProprietaire = ?");
            }
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                echo json_encode(['success' => true, 'fullname' => $result['fullname']]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Utilisateur non trouvé']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;

    default:
        http_response_code(400);
        exit(json_encode(['error'=>'unknown action']));
}
