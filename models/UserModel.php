
<?php
// models/UserModel.php
require_once __DIR__ . '/init_database.php';

class UserModel {

    // ===== ÉTUDIANT =====
    public static function creerEtudiant($data, $photoPath) {
        global $pdo;
        $sql = "INSERT INTO Etudiant (nom, prenom, Email, Tele, MDP, genre, photo, rue, codepostal, ville, Pays)
                VALUES (:nom, :prenom, :email, :tele, :mdp, :genre, :photo, :rue, :codepostal, :ville, :pays)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':tele' => $data['tele'],
            ':mdp' => password_hash($data['mdp'], PASSWORD_DEFAULT),
            ':genre' => $data['genre'],
            ':photo' => $photoPath,
            ':rue' => $data['rue'],
            ':codepostal' => $data['codepostal'],
            ':ville' => $data['ville'],
            ':pays' => $data['pays']
        ]);
    }

    public static function verifierConnexionEtudiant($email, $mdp) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Etudiant WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return ($user && password_verify($mdp, $user['MDP'])) ? $user : false;
    }

    public static function enregistrerPhotoEtudiant($id, $file) {
        $uploadDir = __DIR__ . '/../public/resources/photo_etudiant/';
        $filename = "etudiant_" . intval($id) . ".jpg";
        $targetPath = $uploadDir . $filename;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return "/public/resources/photo_etudiant/$filename";
        }
        /*
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            var_dump(error_get_last());
            exit;
        }
        */
        return false;
    }

    public static function updateEtudiant($id, $data, $photoPath = null) {
        global $pdo;
        $sql = "UPDATE Etudiant SET nom = :nom, prenom = :prenom, Email = :email, Tele = :tele,
                genre = :genre, rue = :rue, codepostal = :codepostal, ville = :ville, Pays = :pays";
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

    // ===== PROPRIÉTAIRE =====
    public static function creerProprietaire($data, $photoPath) {
        global $pdo;
        $sql = "INSERT INTO Propietaire (nom, prenom, Email, Tele, MDP, photo, rue, codepostal, ville, Pays)
                VALUES (:nom, :prenom, :email, :tele, :mdp, :photo, :rue, :codepostal, :ville, :pays)";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':tele' => $data['tele'],
            ':mdp' => password_hash($data['mdp'], PASSWORD_DEFAULT),
            ':photo' => $photoPath,
            ':rue' => $data['rue'],
            ':codepostal' => $data['codepostal'],
            ':ville' => $data['ville'],
            ':pays' => $data['pays']
        ]);
    }

    public static function enregistrerPhotoProprietaire($id, $file) {
        $uploadDir = __DIR__ . '/../public/resources/photo_proprietaire/';
        $filename = "proprietaire_" . intval($id) . ".jpg";
        $targetPath = $uploadDir . $filename;
        if (!is_dir($uploadDir)) {
            //echo "<script>alert('Le dossier n\'existe pas, création du dossier...');</script>";
            mkdir($uploadDir, 0777, true);
        }
        /*else {
            echo "<script>alert('Le dossier existe déjà.');</script>";
        }
        */
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return "/public/resources/photo_proprietaire/$filename";
        }
        /*
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            var_dump(error_get_last());
            exit;
        }
        */
        return false;
    }

    public static function updateProprietaire($id, $data, $photoPath = null) {
        global $pdo;
        $sql = "UPDATE Propietaire SET nom = :nom, prenom = :prenom, Email = :email, Tele = :tele,
                rue = :rue, codepostal = :codepostal, ville = :ville, Pays = :pays";
        if ($photoPath) {
            $sql .= ", photo = :photo";
        }
        $sql .= " WHERE IdPropietaire = :id";

        $stmt = $pdo->prepare($sql);
        $params = [
            ':nom' => $data['nom'],
            ':prenom' => $data['prenom'],
            ':email' => $data['email'],
            ':tele' => $data['tele'],
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

    // ===== AUTH =====
    public static function verifierConnexionProprietaire($email, $mdp) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Propietaire WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return ($user && password_verify($mdp, $user['MDP'])) ? $user : false;
    }

    public static function verifierConnexionAdmin($email, $mdp) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Administrateur WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();
        return ($user && password_verify($mdp, $user['MDP'])) ? $user : false;
    }

    public static function emailExiste($email, $role = 'etudiant') {
        global $pdo;
        switch ($role) {
            case 'etudiant':
                $table = 'Etudiant';
                break;
            case 'proprietaire':
                $table = 'Propietaire';
                break;
            case 'admin':
                $table = 'Administrateur';
                break;
            default:
                $table = null;
        }
        if (!$table) return false;
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE Email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetchColumn() > 0;
    }
}
