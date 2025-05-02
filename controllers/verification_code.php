<?php
session_start();

if (!isset($_POST['email']) || empty($_POST['email'])) {
    http_response_code(400);
    echo "Adresse email manquante.";
    exit;
}

$email = $_POST['email'];

// 生成6位验证码
$code = rand(100000, 999999);

// 保存验证码和时间戳到 SESSION
$_SESSION['email_verification_code'] = $code;
$_SESSION['email_verification_target'] = $email;
$_SESSION['email_verification_time'] = time();

// 构造邮件内容
$subject = "Votre code de vérification";
$message = "Bonjour,\n\nVoici votre code de vérification : $code\nCe code est valide pendant 5 minutes.\n\nMerci.";
$headers = "From: L2164753957@gmail.com";

// 发送邮件（简单版，推荐用 PHPMailer 做更安全的处理）
if (mail($email, $subject, $message, $headers)) {
    echo "Code envoyé à l'adresse : $email";
} else {
    http_response_code(500);
    echo "Erreur lors de l'envoi de l'email. Vérifiez le serveur mail.";
}
?>
