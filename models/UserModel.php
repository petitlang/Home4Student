<?php
// models/UserModel.php
require_once __DIR__ . '/connection.php';

class UserModel {

    // Créer un nouvel étudiant
    public static function creerEtudiant($data, $photoPath) {
        global $pdo;
        $sql = "INSERT INTO Etudiant (nom, prenom, Email, Tele, MDP, genre, garant, photo, rue, codepostal, ville, Pays)
                VALUES (:nom, :prenom, :email, :tele, :mdp, :genre, :garant, :photo, :rue, :codepostal, :ville, :pays)";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':tele' => $data['tele'],
            ':mdp' => password_hash($data['mdp'], PASSWORD_DEFAULT),
            ':genre' => $data['genre'],
            ':garant' => $data['garant'],
            ':photo' => $photoPath,
            ':rue' => $data['rue'],
            ':codepostal' => $data['codepostal'],
            ':ville' => $data['ville'],
            ':pays' => $data['pays']
        ]);
    }

    // Vérifier les identifiants d'un étudiant
    public static function verifierConnexionEtudiant($email, $mdp) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Etudiant WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        if ($user && password_verify($mdp, $user['MDP'])) {
            return $user;
        }
        return false;
    }

    // Enregistrer la photo d'un étudiant
    public static function enregistrerPhotoEtudiant($id, $file) {
        $uploadDir = __DIR__ . '/resources/photo_etudiant/';
        $filename = "etudiant_" . intval($id) . ".jpg";
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return "models/resources/photo_etudiant/$filename";
        }
        return false;
    }

    // Obtenir un étudiant par ID
    public static function getEtudiantById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Etudiant WHERE IdEtudiant = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    // Mettre à jour les infos d'un étudiant
    public static function updateEtudiant($id, $data, $photoPath = null) {
        global $pdo;
        $sql = "UPDATE Etudiant SET nom = :nom, prenom = :prenom, Email = :email, Tele = :tele,
                genre = :genre, garant = :garant, rue = :rue, codepostal = :codepostal,
                ville = :ville, Pays = :pays";

        if ($photoPath) {
            $sql .= ", photo = :photo";
        }
        $sql .= " WHERE IdEtudiant = :id";

        $stmt = $pdo->prepare($sql);
        $params = [
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':tele' => $data['tele'],
            ':genre' => $data['genre'],
            ':garant' => $data['garant'],
            ':rue' => $data['rue'],
            ':codepostal' => $data['codepostal'],
            ':ville' => $data['ville'],
            ':pays' => $data['pays'],
            ':id' => $id
        ];

        if ($photoPath) {
            $params[':photo'] = $photoPath;
        }

        return $stmt->execute($params);
    }

    // Vérifier si un email existe déjà
    public static function emailExiste($email) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Etudiant WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
}
?>