
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Génère un code de vérification aléatoire
    $code = rand(100000, 999999);

    // Stocke le code et l'email dans la session
    $_SESSION['verification_code'] = $code;
    $_SESSION['verification_email'] = $email;

    // Préparation du message
    $subject = "Votre code de vérification - SeLogerFacilement";
    $message = "Bonjour,\n\nVoici votre code de vérification : $code\n\nCe code est valable pour la réinitialisation de votre mot de passe.";
    $headers = "From: no-reply@selogerfacilement.fr\r\n";
    $headers .= "Reply-To: support@selogerfacilement.fr\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

    // Envoi de l'email
    if (mail($email, $subject, $message, $headers)) {
        echo json_encode(["success" => true, "message" => "Code envoyé à votre adresse email."]);
    } else {
        echo json_encode(["success" => false, "message" => "Échec de l'envoi de l'email."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Email non fourni."]);
}
?>
