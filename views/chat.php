<?php
session_start();
$chatList = $_SESSION['chatList'] ?? [];  // 可能为空

// 取第一条会话作为初始显示对象；若没有则用安全的默认值
$firstChat      = $chatList[0] ?? ['idmsg' => 0, 'destinateur' => ''];
$initialIdMsg   = (int)($firstChat['idmsg'] ?? 0);
$initialDestRaw = $firstChat['destinateur'] ?? '';
$initialDest    = htmlspecialchars($initialDestRaw, ENT_QUOTES, 'UTF-8');

// 发送者昵称（当前登录用户），用于区分自己的气泡
$expediteurId = htmlspecialchars($_SESSION['expediteur'] ?? $_SESSION['user_id'] ?? 'Moi', ENT_QUOTES, 'UTF-8');


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Messagerie</title>
    <style>
        :root {
            --primary: #2ecc71;
            --secondary: #e74c3c;
            --accent: #3498db;
            --dark: #2c3e50;
            --light: #ecf0f1;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
            url('https://images.unsplash.com/photo-1515165562835-c4b34a124baa?auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh;
            color: white;
            display: flex;
            flex-direction: column;
        }

        header {
            background-color: rgba(44, 62, 80, 0.95);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: var(--primary);
        }

        .chat-container {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .sidebar {
            width: 25%;
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            padding: 1rem;
            overflow-y: auto;
        }

        .chat-preview {
            border-bottom: 1px solid #ddd;
            padding: 1rem 0;
            cursor: pointer;
        }

        .chat-preview:hover {
            background-color: #f4f4f4;
        }

        .chat-preview .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.8rem;
        }

        .chat-preview-content {
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .chat-box {
            flex: 1;
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .chat-header {
            padding: 1rem 2rem;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
        }

        .chat-header .contact-name {
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--dark);
        }

        .back-btn {
            background: none;
            border: 1px solid #ccc;
            border-radius: 20px;
            padding: 0.4rem 1rem;
            cursor: pointer;
            font-size: 0.9rem;
        }

        .back-btn:hover {
            background-color: #eee;
        }

        .chat-messages {
            flex: 1;
            padding: 1.5rem 2rem;
            overflow-y: auto;
        }

        .message-container {
            display: flex;
            align-items: flex-end;
            margin-bottom: 1rem;
        }

        .message-container.sent {
            justify-content: flex-end;
        }

        .message {
            max-width: 70%;
            padding: 0.8rem 1.2rem;
            border-radius: 20px;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .message.received {
            background-color: #eee;
            color: #333;
            border-bottom-left-radius: 0;
        }

        .message.sent {
            background-color: var(--primary);
            color: white;
            border-bottom-right-radius: 0;
        }

        .avatar-msg {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 0.6rem;
        }

        .chat-input {
            padding: 1rem 2rem;
            border-top: 1px solid #ddd;
            background-color: #fff;
            display: flex;
            gap: 1rem;
        }

        .chat-input input {
            flex: 1;
            padding: 0.8rem;
            border-radius: 25px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .chat-input button {
            background-color: var(--accent);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            cursor: pointer;
            font-weight: bold;
        }

        .chat-input button:hover {
            opacity: 0.85;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">SeLogerFacilement</div>
</header>

<div class="chat-container">
    <!-- 左侧联系人列表（示例静态，两条） -->
    <aside class="sidebar">
        <h3>Messages</h3>
        <?php if (empty($chatList)) : ?>
            <p style="padding: .5rem 1rem; color:#888;">Aucun message pour le moment.</p>
        <?php else : ?>
            <?php foreach ($chatList as $chat) : ?>
                <?php
                // 安全转义 & 类型转换
                $idmsg = (int)$chat['idmsg'];
                $dest  = htmlspecialchars($chat['destinateur'], ENT_QUOTES, 'UTF-8');
                ?>
                <div class="chat-preview"  data-idmsg="<?= $idmsg ?>"  onclick="switchChat('<?= $dest ?>', <?= $idmsg ?>)">
                    <img src="/images/avatar_default.png" class="avatar" alt="avatar">
                    <strong><?= $dest ?></strong>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </aside>

    <!-- 右侧主聊天 -->
    <section class="chat-box">
        <header class="chat-header">
            <span class="contact-name">Contact: <span id="contactName"><?= $initialDest ?></span></span>
            <button class="back-btn" onclick="window.location.href='ads_list.html'">Retour</button>
        </header>

        <div class="chat-messages" id="chatMessages"></div>

        <div class="chat-input">
            <input type="text" id="messageInput" placeholder="Écrivez votre message…">
            <button id="sendBtn">Envoyer</button>
        </div>
    </section>
</div>

<script>
    // === 初始化共享变量 ===
    const expediteur    = "<?= $expediteurId ?>";   // 当前用户昵称
    let   currentIdMsg  = <?= $initialIdMsg ?>;           // 当前对话 (idmsg)
    let   destinateur   = "<?= $initialDest ?>";         // 当前聊天对象

    /* ----------- 1. 发送消息 ----------- */
    document.getElementById('sendBtn').addEventListener('click', async () => {
        const input = document.getElementById('messageInput');
        const msg   = input.value.trim();
        if (!msg || !currentIdMsg) return; // 没有对话时不发送

        await fetch('../controllers/ChatController.php?action=sendMessage', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idMsg: currentIdMsg, expediteur: expediteur, destinateur: destinateur, message: msg })
        });
        input.value = '';
        await loadMessages(true); // 立即刷新
    });

    /* ----------- 2. 拉取消息 ----------- */
    async function loadMessages(scrollBottom = false) {
        if (!currentIdMsg) return; // 没有对话
        try {
            const res  = await fetch(`../controllers/ChatController.php?action=getMessages&idMsg=${currentIdMsg}`);
            const data = await res.json();
            const box  = document.getElementById('chatMessages');
            box.innerHTML = '';
            data.forEach(m => {
                const div   = document.createElement('div');
                const myself = m.expediteur === expediteur;
                div.className = 'message-container ' + (myself ? 'sent' : 'received');
                div.innerHTML = `
                    ${ !myself ? `<img src="/images/avatar_${m.expediteur.toLowerCase()}.png" class="avatar-msg" alt="avatar">` : '' }
                    <div class="message ${myself ? 'sent' : 'received'}">${m.message}</div>
                    ${  myself ? `<img src="/images/avatar_me.png" class="avatar-msg" alt="avatar">` : '' }
                `;
                box.appendChild(div);
            });
            if (scrollBottom) box.scrollTop = box.scrollHeight;
        } catch (err) {
            console.error('loadMessages error', err);
        }
    }
    loadMessages(true);
    setInterval(() => loadMessages(false), 3000);

    /* ----------- 3. 切换联系人 ----------- */
    function switchChat(contactName, idMsg) {
        currentIdMsg = idMsg;
        destinateur  = contactName;
        document.getElementById('contactName').textContent = contactName;
        loadMessages(true);
    }

</script>

</body>
</html>