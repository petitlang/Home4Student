<?php
// controllers/UserController.php
require_once __DIR__ . '/../models/UserModel.php';

session_start();

$action = $_GET['action'] ?? $_POST['action'] ?? null;

switch ($action) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $_POST;
            $role = $_POST['role'] ?? 'etudiant';
            $photoPath = null;

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $photoPath = 'temp'; // temporaire
            }

            if (!UserModel::emailExiste($data['email'], $role)) {
                if ($role === 'etudiant') {
                    UserModel::creerEtudiant($data, $photoPath);
                    $user = UserModel::verifierConnexionEtudiant($data['email'], $data['mdp']);
                    if ($user && isset($_FILES['photo'])) {
                        $photoPath = UserModel::enregistrerPhotoEtudiant($user['IdEtudiant'], $_FILES['photo']);
                        UserModel::updateEtudiant($user['IdEtudiant'], $data, $photoPath);
                    }
                } elseif ($role === 'proprietaire') {
                    UserModel::creerProprietaire($data, $photoPath);
                    $user = UserModel::verifierConnexionProprietaire($data['email'], $data['mdp']);
                    if ($user && isset($_FILES['photo'])) {
                        $photoPath = UserModel::enregistrerPhotoProprietaire($user['IdPropietaire'], $_FILES['photo']);
                        UserModel::updateProprietaire($user['IdPropietaire'], $data, $photoPath);
                    }
                } elseif ($role === 'admin') {
                    // TODO: Ajouter une méthode creerAdministrateur si nécessaire, pour securite +> NON !!
                    echo "<p>Création d'administrateur non implémentée.</p>";
                    exit;
                }

                $_SESSION['user'] = $user;
                $_SESSION['role'] = $role;
                header('Location: ../views/index2.html');
                exit;
            } else {
                echo "<p>Email déjà utilisé pour ce type de compte. <a href='../views/register.html'>Retour</a></p>";
            }
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $mdp = $_POST['password'];
            $role = $_POST['role'] ?? 'etudiant';
            $user = null;

            switch ($role) {
                case 'etudiant':
                    $user = UserModel::verifierConnexionEtudiant($email, $mdp);
                    break;
                case 'proprietaire':
                    $user = UserModel::verifierConnexionProprietaire($email, $mdp);
                    break;
                case 'admin':
                    $user = UserModel::verifierConnexionAdmin($email, $mdp);
                    break;
            }

            if ($user) {
                $_SESSION['user'] = $user;
                $_SESSION['role'] = $role;
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
