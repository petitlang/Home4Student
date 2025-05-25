<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeStudent - Se loger Facilement</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/views/index2.css">
</head>
<body>
<?php include __DIR__ . '/header.php'; ?>

    <div class="hero-slogan">
        <h1>Bienvenue, <?php echo htmlspecialchars($user["prenom"]); ?> !</h1>
    </div>

    <main>
        <section class="hero">
            <div class="container">
                <h1 class="hero-title">Se loger Facilement</h1>
                <p class="hero-subtitle">Pour les étudiants par des étudiants</p>
                
                <div class="grid-cols-2">
                    <div class="benefit-section">
                        <div class="feature-list benefit-list">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p>Des logements fiables, vérifiés par nos équipes</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p>Une communication directe avec le propriétaire</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p>Partout en France</p>
                                </div>
                            </div>
                        </div>
                        <div class="cta">
                            <a href="/views/search.php" class="btn btn-primary btn-icon">
                                <span>Accéder aux annonces</span>
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="feature-card">
                        <div style="height: 300px; background-color: #e2e8f0; display: flex; align-items: center; justify-content: center;">
                            <img class="acc" src="/views/acc.jpg" alt="img">
                        </div>
                        <div class="feature-content">
                            <p class="text-sm text-gray-500">Trouvez facilement votre logement étudiant</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="container">
                <div class="grid-cols-2">
                    <div class="feature-card">
                        <div style="height: 300px; background-color: #e2e8f0; display: flex; align-items: center; justify-content: center;">
                        <img class="acc" src="/views/acc2.jpg" alt="img">
                        </div>
                    </div>
                    <div class="benefit-section">
                        <h2 class="section-title">Vendez vous-même un bien immobilier !</h2>
                        <div class="feature-list benefit-list">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p>Présentez votre bien et ses caractéristiques</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p>Définissez un prix de vente</p>
                                </div>
                            </div>
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div>
                                    <p>Mettez en avant ce qui le rend unique</p>
                                </div>
                            </div>
                        </div>
                        <div class="cta">
                            <a href="/views/mes_annonces.php" class="btn btn-primary btn-icon">
                                <span>Déposer une annonce</span>
                                <i class="fas fa-upload ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php include __DIR__ . '/footer.html'; ?>
</body>
</html>