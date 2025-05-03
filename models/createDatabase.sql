-- Création de la base de données
CREATE DATABASE IF NOT EXISTS Home4Student CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE Home4Student;

-- Table Étudiant
CREATE TABLE IF NOT EXISTS Etudiant (
    IdEtudiant INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    Email VARCHAR(100) UNIQUE,
    Tele VARCHAR(20),
    MDP VARCHAR(255),
    genre VARCHAR(10),
    garant VARCHAR(100),
    photo TEXT,  -- Chemin du fichier photo (ex: uploads/photos/etudiant_1.jpg)
    rue VARCHAR(100),
    codepostal VARCHAR(10),
    ville VARCHAR(50),
    Pays VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Propriétaire
CREATE TABLE IF NOT EXISTS Proprietaire (
    IdProprietaire INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    Email VARCHAR(100) UNIQUE,
    Tele VARCHAR(20),
    MDP VARCHAR(255),
    photo TEXT,  -- Chemin du fichier photo (ex: uploads/photos/prop_1.jpg)
    rue VARCHAR(100),
    codepostal VARCHAR(10),
    ville VARCHAR(50),
    Pays VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Administrateur
CREATE TABLE IF NOT EXISTS Administrateur (
    IdAdministrateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    MDP VARCHAR(255),
    photo TEXT  -- (optionnel) pour permettre à l’admin d’avoir une image aussi
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Annonce
CREATE TABLE IF NOT EXISTS Annonce (
    IdAnnonce INT AUTO_INCREMENT PRIMARY KEY,
    Titre VARCHAR(100),
    Type VARCHAR(50),
    Prix DECIMAL(10,2),
    Etat VARCHAR(20),
    rue VARCHAR(100),
    codepostal VARCHAR(10),
    ville VARCHAR(50),
    Pays VARCHAR(50),
    Descriptions TEXT,
    IdProprietaire INT,
    FOREIGN KEY (IdProprietaire) REFERENCES Proprietaire(IdProprietaire) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Signaler
CREATE TABLE IF NOT EXISTS Signaler (
    IdSignaler INT AUTO_INCREMENT PRIMARY KEY,
    Type VARCHAR(50),
    message TEXT,
    IdEtudiant INT,
    IdAnnonce INT,
    FOREIGN KEY (IdEtudiant) REFERENCES Etudiant(IdEtudiant) ON DELETE CASCADE,
    FOREIGN KEY (IdAnnonce) REFERENCES Annonce(IdAnnonce) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Favoris
CREATE TABLE IF NOT EXISTS Favoris (
    IdFavoris INT AUTO_INCREMENT PRIMARY KEY,
    IdEtudiant INT,
    IdAnnonce INT,
    FOREIGN KEY (IdEtudiant) REFERENCES Etudiant(IdEtudiant) ON DELETE CASCADE,
    FOREIGN KEY (IdAnnonce) REFERENCES Annonce(IdAnnonce) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Candidature
CREATE TABLE IF NOT EXISTS Candidature (
    IdCandidature INT AUTO_INCREMENT PRIMARY KEY,
    IdEtudiant INT,
    IdAnnonce INT,
    FOREIGN KEY (IdEtudiant) REFERENCES Etudiant(IdEtudiant) ON DELETE CASCADE,
    FOREIGN KEY (IdAnnonce) REFERENCES Annonce(IdAnnonce) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table FAQ
CREATE TABLE IF NOT EXISTS FAQ (
    IdFAQ INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT,
    reponse TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Chat (expediteur/destinateur polymorphique, à gérer côté code PHP)
CREATE TABLE IF NOT EXISTS Chat (
    IdChat INT AUTO_INCREMENT PRIMARY KEY,
    IdMsg INT,
    message TEXT,
    temps DATETIME DEFAULT CURRENT_TIMESTAMP,
    expediteur TEXT,
    destinateur TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table PhotoAnnonce : contient les chemins des photos pour chaque annonce
CREATE TABLE IF NOT EXISTS PhotoAnnonce (
    IdPhoto INT AUTO_INCREMENT PRIMARY KEY,
    chemin TEXT, -- ex: uploads/photos/ads/annonce_5_1.jpg
    IdAnnonce INT,
    FOREIGN KEY (IdAnnonce) REFERENCES Annonce(IdAnnonce) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
