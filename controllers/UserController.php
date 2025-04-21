<?php
// controllers/UserController.php
require_once __DIR__ . '/../models/UserModel.php';

session_start();

// Routeur simple basé sur l'action GET ou POST
$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $photoPath = null;

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                // Enregistrement temporaire, sans ID
                $photoPath = 'temp';
            }

            if (!UserModel::emailExiste($data['email'])) {
                UserModel::creerEtudiant($data, $photoPath);

                // Récupère l'étudiant pour son ID (après insertion)
                $user = UserModel::verifierConnexionEtudiant($data['email'], $data['mdp']);

                if ($user && isset($_FILES['photo'])) {
                    $photoPath = UserModel::enregistrerPhotoEtudiant($user['IdEtudiant'], $_FILES['photo']);
                    UserModel::updateEtudiant($user['IdEtudiant'], $data, $photoPath);
                }

                $_SESSION['user'] = $user;
                header('Location: ../views/index2.html');
                exit;
            } else {
                echo "<p>Email déjà utilisé. <a href='../views/register.html'>Retour</a></p>";
            }
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $mdp = $_POST['password'];
            $user = UserModel::verifierConnexionEtudiant($email, $mdp);

            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: ../views/index2.html');
                exit;
            } else {
                echo "<p>Identifiants incorrects. <a href='../views/login.html'>Retour</a></p>";
            }
        }
        break;

    case 'logout':
        session_destroy();
        header('Location: ../views/index.html');
        exit;

    default:
        echo "<p>Aucune action valide spécifiée.</p>";
        break;
}
?>