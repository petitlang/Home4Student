<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer mon profil</title>
    <link rel="stylesheet" href="/views/profile-edit.css">
</head>
<body>
    <?php include __DIR__ . '/header.php'; ?>

    <div class="login-section">
        <div class="login-container">
            <h2>Compléter mon profil</h2>
            
            <form action="../controllers/UserController.php?action=update_profile" method="POST" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" required placeholder="Votre prénom">
                </div>
                <div class="input-group">
                    <label for="nom">Nom</label>
                    <input type="text" name="nom" id="nom" required placeholder="Votre nom">
                </div>
                <div class="input-group">
                    <label for="tele">Numéro de téléphone</label>
                    <input type="tel" name="tele" id="tele" required placeholder="Votre téléphone">
                </div>
                <div class="input-group">
                    <label for="photo">Photo de profil</label>
                    <input type="file" name="photo" id="photo" accept="image/*">
                </div>
                <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </form>

        </div>
    </div>

    <?php include __DIR__ . '/footer.html'; ?>

<script>
// JS réduit : actions de redirection uniquement
document.getElementById('homePageLink')?.addEventListener('click', function(e) {
    e.preventDefault();
    window.location.href = '/views/index2.php';
});
</script>

</body>
</html>