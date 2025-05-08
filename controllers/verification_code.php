<?php
session_start();

if (!isset($_POST['email']) || empty($_POST['email'])) {
    http_response_code(400);
    echo "Adresse email manquante.";
    exit;
}

$email = $_POST['email'];

// generer un code de vérification aléatoire
$code = rand(100000, 999999);

// enrgistre code et time dans SESSION
$_SESSION['email_verification_code'] = $code;
$_SESSION['email_verification_target'] = $email;
$_SESSION['email_verification_time'] = time();

// contenu du mail
$subject = "Votre code de vérification";
$message = "Bonjour,\n\nVoici votre code de vérification : $code\nCe code est valide pendant 5 minutes.\n\nMerci.";
$headers = "From: L2164753957@gmail.com";

// send mail
if (mail($email, $subject, $message, $headers)) {
    echo "Code envoyé à l'adresse : $email";
} else {
    http_response_code(500);
    echo "Erreur lors de l'envoi de l'email. Vérifiez le serveur mail.";
}

echo 'Session ID: ' . session_id();

?>
