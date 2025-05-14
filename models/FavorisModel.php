<?php
require_once __DIR__ . '/1/connection.php';

/**
 * 添加收藏
 */
function add_favoris($userId, $annonceId, $role = 'etudiant') {
    global $pdo;
    
    try {
        if ($role === 'proprietaire') {
            $stmt = $pdo->prepare("INSERT INTO Favoris (IdProprietaire, IdAnnonce) VALUES (?, ?)");
        } else {
            $stmt = $pdo->prepare("INSERT INTO Favoris (IdEtudiant, IdAnnonce) VALUES (?, ?)");
        }
        $stmt->execute([$userId, $annonceId]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de l'ajout aux favoris: " . $e->getMessage());
        return false;
    }
}

/**
 * 移除收藏
 */
function remove_favoris($userId, $annonceId, $role = 'etudiant') {
    global $pdo;
    
    try {
        if ($role === 'proprietaire') {
            $stmt = $pdo->prepare("DELETE FROM Favoris WHERE IdProprietaire = ? AND IdAnnonce = ?");
        } else {
            $stmt = $pdo->prepare("DELETE FROM Favoris WHERE IdEtudiant = ? AND IdAnnonce = ?");
        }
        $stmt->execute([$userId, $annonceId]);
        return true;
    } catch (PDOException $e) {
        error_log("Erreur lors de la suppression des favoris: " . $e->getMessage());
        return false;
    }
}

/**
 * 检查是否已收藏
 */
function is_favoris($userId, $annonceId, $role = 'etudiant') {
    global $pdo;
    
    try {
        if ($role === 'proprietaire') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Favoris WHERE IdProprietaire = ? AND IdAnnonce = ?");
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Favoris WHERE IdEtudiant = ? AND IdAnnonce = ?");
        }
        $stmt->execute([$userId, $annonceId]);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log("Erreur lors de la vérification des favoris: " . $e->getMessage());
        return false;
    }
}

/**
 * 获取用户的所有收藏（带分页）
 */
function get_user_favoris($userId, $page = 1, $perPage = 9, $role = 'etudiant') {
    global $pdo;
    
    try {
        if ($role === 'proprietaire') {
            $countSql = "SELECT COUNT(*) FROM Favoris WHERE IdProprietaire = ?";
            $dataSql = "SELECT a.*, f.IdFavoris FROM Annonce a INNER JOIN Favoris f ON a.IdAnnonce = f.IdAnnonce WHERE f.IdProprietaire = ? ORDER BY f.IdFavoris DESC LIMIT ? OFFSET ?";
        } else {
            $countSql = "SELECT COUNT(*) FROM Favoris WHERE IdEtudiant = ?";
            $dataSql = "SELECT a.*, f.IdFavoris FROM Annonce a INNER JOIN Favoris f ON a.IdAnnonce = f.IdAnnonce WHERE f.IdEtudiant = ? ORDER BY f.IdFavoris DESC LIMIT ? OFFSET ?";
        }
        $stmt = $pdo->prepare($countSql);
        $stmt->execute([$userId]);
        $total = $stmt->fetchColumn();
        
        // 计算总页数
        $totalPages = ceil($total / $perPage);
        
        // 确保页码有效
        $page = max(1, min($page, $totalPages));
        
        // 计算偏移量
        $offset = ($page - 1) * $perPage;
        
        // 获取分页数据
        $stmt = $pdo->prepare($dataSql);
        $stmt->execute([$userId, $perPage, $offset]);
        $favoris = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'favoris' => $favoris,
            'total' => $total,
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'perPage' => $perPage
        ];
    } catch (PDOException $e) {
        error_log("Erreur lors de la récupération des favoris: " . $e->getMessage());
        return [
            'favoris' => [],
            'total' => 0,
            'totalPages' => 0,
            'currentPage' => 1,
            'perPage' => $perPage
        ];
    }
}

/**
 * 获取用户的收藏数量
 */
function get_favoris_count($userId, $role = 'etudiant') {
    global $pdo;
    
    try {
        if ($role === 'proprietaire') {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Favoris WHERE IdProprietaire = ?");
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Favoris WHERE IdEtudiant = ?");
        }
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log("Erreur lors du comptage des favoris: " . $e->getMessage());
        return 0;
    }
} 