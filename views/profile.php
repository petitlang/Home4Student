<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer mon profil</title>
    <link rel="stylesheet" href="/views/profile.css">
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
                <input type="hidden" name="role" value="<?php echo $_SESSION['role']; ?>">
                <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </form>

        </div>
    </div>
    <?php include __DIR__ . '/footer.html'; ?>
    <script>
        window.addEventListener('load', function() {
            var email = localStorage.getItem('connectedUserEmail');
            var users = JSON.parse(localStorage.getItem('users')) || [];
            var user = users.find(u => u.email === email);

            if (!email || !user) {
                window.location.href = '/views/login.php';
            } else {
                // Pré-remplir les champs si le profil existe
                document.getElementById('firstName').value = user.firstName || '';
                document.getElementById('lastName').value = user.lastName || '';
                document.getElementById('phoneNumber').value = user.phoneNumber || '';
            }
        });

        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();

            var email = localStorage.getItem('connectedUserEmail');
            var users = JSON.parse(localStorage.getItem('users')) || [];
            var userIndex = users.findIndex(u => u.email === email);

            var firstName = document.getElementById('firstName').value;
            var lastName = document.getElementById('lastName').value;
            var phoneNumber = document.getElementById('phoneNumber').value;
            var file = document.getElementById('profileImage').files[0];

            // Validation supplémentaire pour s'assurer que le numéro de téléphone est rempli
            if (!phoneNumber) {
                alert('Veuillez entrer votre numéro de téléphone.');
                return;
            }

            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    users[userIndex] = {
                        ...users[userIndex],
                        firstName: firstName,
                        lastName: lastName,
                        phoneNumber: phoneNumber,
                        profileImage: e.target.result
                    };
                    localStorage.setItem('users', JSON.stringify(users));
                    window.location.href = '/views/index2.php';
                };
                reader.readAsDataURL(file);
            } else {
                users[userIndex] = {
                    ...users[userIndex],
                    firstName: firstName,
                    lastName: lastName,
                    phoneNumber: phoneNumber,
                    profileImage: users[userIndex].profileImage || ''
                };
                localStorage.setItem('users', JSON.stringify(users));
                window.location.href = '/views/index2.php';
            }
        });

        // Ajout de la vérification avant la redirection
        document.getElementById('homePageLink').addEventListener('click', function(e) {
            e.preventDefault();

            var firstName = document.getElementById('firstName').value;
            var lastName = document.getElementById('lastName').value;
            var phoneNumber = document.getElementById('phoneNumber').value;

            if (!firstName && !lastName && !phoneNumber) {
                window.location.href = '/views/index.php';
            } else {
                window.location.href = '/views/index2.php';
            }
        });
    </script>
</body>
</html>