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

            if (
                !isset($_POST['code'], $_SESSION['email_verification_code'], $_SESSION['email_verification_target'], $_SESSION['email_verification_time']) ||
                $_POST['email'] !== $_SESSION['email_verification_target'] ||
                $_POST['code'] !== strval($_SESSION['email_verification_code']) ||
                time() - $_SESSION['email_verification_time'] > 300 // 超过5分钟
            ) {
                header("Location: /views/register.html?error=verification");
                exit;
            }   

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
                        $user = UserModel::verifierConnexionEtudiant($data['email'], $data['mdp']);
                    }
                } elseif ($role === 'proprietaire') {
                    UserModel::creerProprietaire($data, $photoPath);
                    $user = UserModel::verifierConnexionProprietaire($data['email'], $data['mdp']);
                    if ($user && isset($_FILES['photo'])) {
                        $photoPath = UserModel::enregistrerPhotoProprietaire($user['IdProprietaire'], $_FILES['photo']);
                        UserModel::updateProprietaire($user['IdProprietaire'], $data, $photoPath);
                        $user = UserModel::verifierConnexionProprietaire($data['email'], $data['mdp']);
                    }
                } elseif ($role === 'admin') {
                    // TODO: Ajouter une méthode creerAdministrateur si nécessaire, pour securite +> NON !!
                    echo "<p>Création d'administrateur non implémentée.</p>";
                    exit;
                }

                if ($user) {
                    if ($role === 'etudiant' && isset($user['IdEtudiant'])) {
                        $user['id'] = $user['IdEtudiant'];
                    } elseif ($role === 'proprietaire' && isset($user['IdProprietaire'])) {
                        $user['id'] = $user['IdProprietaire'];
                    } elseif ($role === 'admin' && isset($user['IdAdmin'])) {
                        $user['id'] = $user['IdAdmin'];
                    }
                    $_SESSION['user'] = $user;
                    $_SESSION['role'] = $role;
                    header('Location: ../views/index2.php');
                    exit;
                }
            } else {
                echo "<p>Email déjà utilisé pour ce type de compte. <a href='../views/register.html'>Retour</a></p>";
            }
        }
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $mdp = $_POST['password'];
            $role = $_POST['role'];
            //$role = (isset($_POST['role']) && trim($_POST['role']) !== '') ? $_POST['role'] : $user['role'];
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

                default:
                    echo "<p>Rôle non reconnu. <a href='../views/login.php'>Retour</a></p>";
                    exit;
            }

            if ($user && $user !== false) {
                if ($role === 'etudiant' && isset($user['IdEtudiant'])) {
                    $user['id'] = $user['IdEtudiant'];
                } elseif ($role === 'proprietaire' && isset($user['IdProprietaire'])) {
                    $user['id'] = $user['IdProprietaire'];
                } elseif ($role === 'admin' && isset($user['IdAdmin'])) {
                    $user['id'] = $user['IdAdmin'];
                }
                $_SESSION['user'] = $user;
                $_SESSION['role'] = $role;
                header('Location: ../views/index2.php');
                exit;
            } else {
                //echo "<p>user est : $user<p>";
                unset($user);
                session_destroy();
                echo "<p>Identifiants ou MDP incorrects. <a href='../views/login.php'>Retour</a></p>";
            }
        }
        break;

    case 'logout':
        session_destroy();
        header('Location: ../views/index.php');
        exit;

    case 'reset_password':
        

        //echo "<script>alert('reset_password : Rôle est : " . $_POST['role'] . ", code est : " . $_POST['code'] . " ,user est : " . $_SESSION['email_verification_code'] . "');</script>";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPwd = $_POST['new_password'];
            $email = $_POST['email'];
            $role = $_POST['role'];
            /*
            $code = $_POST['code'] ?? null;
            $verificationCode = $_SESSION['email_verification_code'] ?? null;
            */

            // verification du code de vérification
            $_SESSION['POST_CODE'] = $_POST['code'];
            if (!isset($_SESSION['email_verification_time']) || time() - $_SESSION['email_verification_time'] > 300) {
                header("Location: ../views/reset_password.php?error=timeout");
                exit;
            }
            if (!isset($_SESSION['email_verification_target']) || $_POST['email'] !== $_SESSION['email_verification_target']) {
                header("Location: ../views/reset_password.php?error=email");
                exit;
            }
            if (!isset($_SESSION['email_verification_code']) || $_POST['code'] !== strval($_SESSION['email_verification_code'])) {
                header("Location: ../views/reset_password.php?error=verification");
                exit;
            }

            $hashedPwd = password_hash($newPwd, PASSWORD_DEFAULT);
            $user = $_SESSION['user'];
            $id = ($role === 'etudiant') ? $user['IdEtudiant'] : $user['IdProprietaire'];

            // update password in the database
            if ($role === 'etudiant') {
                $pdo->prepare("UPDATE Etudiant SET MDP = :mdp WHERE IdEtudiant = :id")
                    ->execute([':mdp' => $hashedPwd, ':id' => $id]);
            } elseif ($role === 'proprietaire') {
                $pdo->prepare("UPDATE Proprietaire SET MDP = :mdp WHERE IdProprietaire = :id")
                    ->execute([':mdp' => $hashedPwd, ':id' => $id]);
            }

            echo "<script>alert('Mot de passe modifié avec succès !'); window.location.href = '../views/login.php';</script>";
            exit;
        } else {
            echo "<p>Échec de la vérification. Reconnectez-vous ou code invalide.</p>";
        }
        break;


    case 'update_profile':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            //$role = $_POST['role'] ?? $user['role'];
            $role = (isset($_POST['role']) && trim($_POST['role']) !== '') ? $_POST['role'] : $user['role'];
            $photoPath = null;

            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                if ($role === 'etudiant') {
                    $photoPath = UserModel::enregistrerPhotoEtudiant($user['IdEtudiant'], $_FILES['photo']);
                    if ($photoPath) {
                        echo "<script>alert('Photo étudiant mise à jour ! Path: " . $photoPath . "');</script>";
                    } else {
                        echo "<script>alert('Erreur lors de l\'enregistrement de la photo étudiant.');</script>";
                    }
                } elseif ($role === 'proprietaire') {
                    $photoPath = UserModel::enregistrerPhotoProprietaire($user['IdProprietaire'], $_FILES['photo']);
                    if ($photoPath) {
                        echo "<script>alert('Photo propriétaire mise à jour ! Path: " . $photoPath . "');</script>";
                    } else {
                        echo "<script>alert('Erreur lors de l\'enregistrement de la photo propriétaire.');</script>";
                    }
                } else {
                    echo "<script>alert('Rôle non reconnu : " . $role . "; User est " . $user['role'] . "');</script>";
                }
            } else {
                echo "<script>alert('Aucune photo téléchargée ou erreur lors du téléchargement. Code erreur: " . ($_FILES['photo']['error'] ?? 'non défini') . "');</script>";
            }

            if ($role === 'etudiant') {
                UserModel::updateEtudiant($user['IdEtudiant'], $_POST, $photoPath);
                UserModel::verifierConnexionEtudiant($user['Email'], $_POST['mdp']);
            } elseif ($role === 'proprietaire') {
                UserModel::updateProprietaire($user['IdProprietaire'], $_POST, $photoPath);
                UserModel::verifierConnexionProprietaire($user['Email'], $_POST['mdp']);
            }

            echo "<script>alert('Profil mis à jour !'); window.location.href = '../views/index2.php';</script>";
            exit;
        }
        break;

    default:
        echo "<p>Aucune action valide spécifiée.</p>";
        break;
}
