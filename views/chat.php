<?php
session_start();
$chatList = $_SESSION['chatList'] ?? [];  // 可能为空

// 取第一条会话作为初始显示对象；若没有则用安全的默认值
$firstChat      = $chatList[0] ?? ['idchat' => 0, 'destinateur' => ''];
$initialIdChat  = (int)($firstChat['idchat'] ?? 0);
$initialDestRaw = $firstChat['destinateur'] ?? '';
$initialDest    = htmlspecialchars($initialDestRaw, ENT_QUOTES, 'UTF-8');

// 发送者昵称（当前登录用户），用于区分自己的气泡
$expediteurId = htmlspecialchars($_SESSION['expediteur'] ?? $_SESSION['user']['id'] ?? 'Moi', ENT_QUOTES, 'UTF-8');


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Messagerie</title>
    <style>
        :root {
            --primary-color: #6b9080;
            --primary-dark: #4d6a5c;
            --primary-light: #a4c3b2;
            --secondary-color: #f6fff8;
            --text-dark: #2c3e50;
            --text-light: #7f8c8d;
            --white: #ffffff;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-image: url("https://i.pinimg.com/originals/91/c9/6e/91c96ef6fff1f9a1543ef6660ecaf5fc.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
        }

        .header-top {
            background-color: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            height: 55px;
        }

        .logo {
            height: 75px;
            object-fit: contain;
        }

        .header-bottom {
            background-color: var(--primary-color);
            padding: 0.5rem 2rem;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .nav-center {
            flex: 1;
            display: flex;
            justify-content: center;
        }

        .nav-links {
            display: flex;
            gap: 0.5rem;
        }

        .nav-links a {
            color: white;
            background-color: var(--primary-dark);
            padding: 0.4rem 0.8rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .nav-links a:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        .nav-buttons a {
            margin-left: 0.5rem;
            padding: 0.4rem 0.8rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-outline {
            background-color: white;
            color: var(--text-dark);
            border: 1px solid #ccc;
        }

        .btn-outline:hover {
            background-color: #f0f0f0;
        }

        .btn-solid {
            background-color: var(--primary-dark);
            color: white;
            border: none;
        }

        .btn-solid:hover {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        /* 聊天主容器和内容区样式，保持原有布局但色号风格统一 */
        .chat-container {
            display: flex;
            height: 70vh;
            margin: 2rem auto;
            max-width: 1100px;
            background: rgba(255,255,255,0.95);
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        .sidebar {
            width: 25%;
            background: var(--primary-light);
            color: var(--text-dark);
            padding: 1.5rem 1rem;
            overflow-y: auto;
        }
        .chat-preview {
            border-bottom: 1px solid #e0e0e0;
            padding: 1rem 0;
            cursor: pointer;
            transition: var(--transition);
            border-radius: 0.5rem;
        }
        .chat-preview:hover {
            background-color: var(--primary-color);
            color: white;
        }
        .chat-preview .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 0.8rem;
            box-shadow: var(--shadow);
        }
        .chat-box {
            flex: 1;
            background: var(--white);
            color: var(--text-dark);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-radius: 0 10px 10px 0;
        }
        .chat-header {
            padding: 1rem 2rem;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--primary-light);
            border-top-right-radius: 10px;
        }
        .chat-header .contact-name {
            font-weight: bold;
            font-size: 1.2rem;
            color: var(--primary-dark);
        }
        .back-btn {
            background: none;
            border: 1px solid var(--primary-color);
            border-radius: 20px;
            padding: 0.4rem 1rem;
            cursor: pointer;
            font-size: 0.95rem;
            color: var(--primary-color);
            transition: var(--transition);
        }
        .back-btn:hover {
            background-color: var(--primary-color);
            color: var(--white);
        }
        .chat-messages {
            flex: 1;
            padding: 1.5rem 2rem;
            overflow-y: auto;
            background: var(--secondary-color);
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
            border-radius: 1.2rem;
            font-size: 1rem;
            line-height: 1.5;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        .message.received {
            background-color: var(--primary-light);
            color: var(--text-dark);
            border-bottom-left-radius: 0.2rem;
        }
        .message.sent {
            background-color: var(--primary-color);
            color: var(--white);
            border-bottom-right-radius: 0.2rem;
        }
        .avatar-msg {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 0.6rem;
            box-shadow: var(--shadow);
        }
        .chat-input {
            padding: 1rem 2rem;
            border-top: 1px solid #e0e0e0;
            background-color: var(--white);
            display: flex;
            gap: 1rem;
            border-bottom-right-radius: 10px;
        }
        .chat-input input {
            flex: 1;
            padding: 0.8rem;
            border-radius: 1.5rem;
            border: 1px solid var(--primary-color);
            font-size: 1rem;
            background: var(--secondary-color);
            color: var(--text-dark);
            transition: var(--transition);
        }
        .chat-input input:focus {
            outline: none;
            border-color: var(--primary-dark);
            background: var(--white);
        }
        .chat-input button {
            background-color: var(--primary-color);
            color: var(--white);
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 1.5rem;
            cursor: pointer;
            font-weight: bold;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        .chat-input button:hover {
            background-color: var(--primary-dark);
            color: var(--white);
            transform: translateY(-2px);
        }
        /* Footer样式 */
        footer {
            background-color: var(--primary-color);
            color: white;
            padding: 2rem 1rem;
        }
        .footer-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: flex-start;
        }
        .footer-section {
            flex: 1;
            min-width: 180px;
            margin-bottom: 1rem;
        }
        .footer-section h4 {
            margin-bottom: 1rem;
        }
        .footer-section a {
            display: block;
            color: white;
            text-decoration: none;
            margin: 0.3rem 0;
        }
        .footer-section a:hover {
            text-decoration: underline;
        }
        .social-icons {
            display: flex;
            gap: 1rem;
            font-size: 1.5rem;
        }
        .social-icons a {
            color: white;
        }
        @media (max-width: 900px) {
            .chat-container {
                flex-direction: column;
                height: auto;
            }
            .sidebar {
                width: 100%;
                min-height: 120px;
                box-shadow: none;
                margin-bottom: 1rem;
            }
            .chat-box {
                margin: 0;
            }
            .footer-grid {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <header>
      <div class="header-top">
        <img src="/views/logo-removebg-preview.png" alt="Logo Home4Student" class="logo" />
      </div>
      <div class="header-bottom">
        <nav class="navbar">
          <div class="nav-center">
            <div class="nav-links">
              <a href="/views/ads_list.php">Offres</a>
              <a href="/views/chat.php">Messagerie</a>
              <a href="/views/faq_back.html">FAQ</a>
              <a href="/views/contact.html">Contact</a>
              <a href="/views/cgu.html">CGU</a>
            </div>
          </div>
          <div class="nav-buttons">
            <a href="/views/login.html" class="btn-solid">Se connecter</a>
            <a href="/views/register.html" class="btn-outline">S'inscrire</a>
          </div>
        </nav>
      </div>
    </header>

<div class="chat-container">
    <!-- 左侧联系人列表（示例静态，两条） -->
    <aside class="sidebar">
        <h3>Messages</h3>
        <button id="addContactBtn" style="margin: 0.5rem 0; width: 100%;">+ Nouveau contact</button>
        <div id="addContactForm" style="display:none; margin-bottom:1rem; background:#f9f9f9; padding:1rem; border-radius:8px;">
            <div>
                <input type="text" id="newContactId" placeholder="ID du destinataire, ex: 13" style="width:90%;margin-bottom:0.5rem;">
            </div>
            <div>
                <select id="newContactRole" style="width:95%;margin-bottom:0.5rem;">
                    <option value="proprietaire">propriétaire</option>
                    <option value="etudiant">étudiant</option>
                </select>
            </div>
            <button id="confirmAddContact" style="width:100%;">Confirmer</button>
        </div>
        <?php if (empty($chatList)) : ?>
            <p style="padding: .5rem 1rem; color:#888;">Aucun message pour le moment.</p>
        <?php else : ?>
            <?php foreach ($chatList as $chat) : ?>
                <?php
                // 安全转义 & 类型转换
                $idchat = (int)$chat['idchat'];
                $dest  = htmlspecialchars($chat['destinateur'], ENT_QUOTES, 'UTF-8');
                ?>
                <div class="chat-preview"  data-idchat="<?= $idchat ?>"  onclick="switchChat('<?= $dest ?>', <?= $idchat ?>)">
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
    let   currentIdChat = <?= $initialIdChat ?>;           // 当前对话 (idchat)
    let   destinateur   = "<?= $initialDest ?>";         // 当前聊天对象

    /* ----------- 1. 发送消息 ----------- */
    document.getElementById('sendBtn').addEventListener('click', async () => {
        const input = document.getElementById('messageInput');
        const msg   = input.value.trim();
        if (!msg || !currentIdChat) return; // 没有对话时不发送

        await fetch('../controllers/ChatController.php?action=sendMessage', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ idChat: currentIdChat, expediteur: expediteur, destinateur: destinateur, message: msg })
        });
        input.value = '';
        await loadMessages(true); // 立即刷新
    });

    /* ----------- 2. 拉取消息 ----------- */
    async function loadMessages(scrollBottom = false) {
        if (!currentIdChat) return; // 没有对话
        try {
            const res  = await fetch(`../controllers/ChatController.php?action=getMessages&idChat=${currentIdChat}`);
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
    function switchChat(contactName, idChat) {
        currentIdChat = idChat;
        destinateur  = contactName;
        document.getElementById('contactName').textContent = contactName;
        loadMessages(true);
    }

    // Afficher/masquer le formulaire d'ajout de contact
    document.getElementById('addContactBtn').onclick = function() {
        const form = document.getElementById('addContactForm');
        form.style.display = (form.style.display === 'none' ? 'block' : 'none');
    };

    // Cliquer sur confirmer pour démarrer une nouvelle conversation
    document.getElementById('confirmAddContact').onclick = async function() {
        const id = document.getElementById('newContactId').value.trim();
        const role = document.getElementById('newContactRole').value;
        if (!id || !role) {
            alert('Veuillez remplir toutes les informations.');
            return;
        }
        
        // 组合新的联系人ID
        const contactName = role + '-' + id;
        
        try {
            // 保存到session
            const response = await fetch('../controllers/ChatController.php?action=createChat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    expediteur: expediteur,
                    destinateur: contactName
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // 动态添加到左侧联系人列表
                const sidebar = document.querySelector('.sidebar');
                const newDiv = document.createElement('div');
                newDiv.className = 'chat-preview';
                newDiv.setAttribute('data-idchat', result.idChat);
                newDiv.onclick = function() { switchChat(contactName, result.idChat); };
                newDiv.innerHTML = `
                    <img src="/images/avatar_default.png" class="avatar" alt="avatar">
                    <strong>${contactName}</strong>
                `;
                
                // 插入到"Messages"标题和表单之后
                sidebar.insertBefore(newDiv, sidebar.children[sidebar.children.length - <?php echo empty($chatList) ? 1 : 0; ?>]);
                
                // 切换到新会话
                switchChat(contactName, result.idChat);
                document.getElementById('addContactForm').style.display = 'none';
                document.getElementById('messageInput').focus();
            } else {
                alert('Échec de la création de la conversation : ' + (result.error || 'Erreur inconnue'));
            }
        } catch (error) {
            console.error('Erreur lors de la création de la conversation:', error);
            alert('Une erreur est survenue lors de la création de la conversation. Veuillez réessayer plus tard.');
        }
    };

</script>

<!-- FOOTER -->
<footer>
  <div class="footer-grid">
    <div class="footer-section">
      <div class="social-icons">
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="fab fa-linkedin-in"></i></a>
      </div>
    </div>
    <div class="footer-section">
      <h4>L'entreprise</h4>
      <a href="#">Qui sommes-nous ?</a>
      <a href="/views/contact.html">Nous contacter</a>
    </div>
    <div class="footer-section">
      <h4>Services pro</h4>
      <a href="#">Tous nos services</a>
      <a href="#">Accès client</a>
    </div>
    <div class="footer-section">
      <h4>À découvrir</h4>
      <a href="#">Tout l'immobilier</a>
      <a href="#">Toutes les villes</a>
      <a href="#">Tous les départements</a>
    </div>
  </div>
</footer>

</body>
</html>